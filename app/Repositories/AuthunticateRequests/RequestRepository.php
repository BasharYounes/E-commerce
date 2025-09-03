<?php 

namespace App\Repositories\AuthunticateRequests;

use App\Models\AuthunticateRequest;
use Auth;

class RequestRepository
{
    public function findRequestByUser_id()
    {
        return AuthunticateRequest::where('user_id', Auth::id())
                                    ->where('status', 'pending')
                                    ->first();
    }

    public function storeDocument($request)
    {
        return $request->file('document')->store('authenticate_documents','public');
    }

    public function storeRequest($documentPath)
    {
        $data=[];
        $data['user_id'] = Auth::id();
        $data['document_path'] = 'storage/'.$documentPath;
        $data['status'] = 'pending';
        return AuthunticateRequest::create($data);
    }

    public function allPendingRequests($status)
    {
        return AuthunticateRequest::with('user')
                        ->where('status', $status)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
    }

    public function findRequest($requestId)
    {
        return AuthunticateRequest::with('user')->findOrFail($requestId);
    }

    public function updateRequest($authRequest,$data)
    {
        $authRequest->update($data);
    }

    public function getAllUserRequests()
    {
        return AuthunticateRequest::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->first();
    }
}