<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\AuthunticateRequest;

class AuthunticateRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,zip,rar|max:5120' // 5MB كحد أقصى
        ]);

        $existingRequest = AuthunticateRequest::where('user_id', Auth::id())
                                            ->where('status', 'pending')
                                            ->first();
                                            
        if ($existingRequest) {
            return response()->json([
                'error' => 'لديك طلب توثيق قيد المراجعة بالفعل'
            ], 400);
        }

        $documentPath = $request->file('document')->store('authenticate_documents','public');

        $authRequest = AuthunticateRequest::create([
            'user_id' => Auth::id(),
            'document_path' => 'storage/'.$documentPath,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'تم تقديم طلب التوثيق بنجاح وسيتم مراجعته قريباً',
            'data' => $authRequest
        ], 201);
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $requests = AuthunticateRequest::with('user')
                        ->where('status', $status)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return response()->json(['data' => $requests]);
    }

    public function approve($requestId)
    {
        $authRequest = AuthunticateRequest::with('user')->findOrFail($requestId);
        
        $authRequest->update([
            'status' => 'approved',
            'processed_at' => now()
        ]);

        $authRequest->user->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return response()->json([
            'message' => 'تم الموافقة على طلب التوثيق',
            'data' => $authRequest
        ]);
    }

    public function reject(Request $request, $requestId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $authRequest = AuthunticateRequest::findOrFail($requestId);
        
        $authRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'processed_at' => now()
        ]);

        return response()->json([
            'message' => 'تم رفض طلب التوثيق',
            'data' => $authRequest
        ]);
    }

    // الحصول على حالة طلبي (للمستخدم العادي)
    public function myRequest()
    {
        $authRequest = AuthunticateRequest::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->first();

        return response()->json(['data' => $authRequest]);
    }
}
