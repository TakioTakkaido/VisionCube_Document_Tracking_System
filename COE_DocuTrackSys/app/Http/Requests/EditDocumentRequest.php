<?php

namespace App\Http\Requests;

use App\Models\FileExtension;
use Illuminate\Foundation\Http\FormRequest;

class EditDocumentRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        $series = $this->input('seriesRequired') === 'true' ? 'required|number|max:9999|min:0' : 'nullable|integer';
        $memo = $this->input('memoRequired') === 'true' ? 'required|number|max:9999|min:0' : 'nullable|integer';
        $file = $this->file('file') ? 'required|file|mimes:,'.FileExtension::getFileExtensions() : 'nullable';

        return [
            'type' => 'required|string',
            'sender' => 'required|string|max:255',
            'series_number' => $series,
            'memo_number' => $memo,
            'files.*' => $file,
            'files' => 'nullable|max:5',
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|string',
            'assignee' => 'required|string',
            'document_date' => 'required',
            'description' => 'required'
        ];
    }

    public function messages() : array {
        return [
            'type.required' => 'Document type is required!',

            'sender.required' => 'Document sender is required!',
            
            'sender.max' => 'Sender name can only have up to 255 characters!',

            'recipient.required' => 'Document recipient is required!',
            'recipient.max' => 'Recipient name can only have up to 255 characters!',

            'series.required' => 'Series number required for memoranda!',
            'series.min' => 'Series number invalid (Must be between 1-9999 only)!',
            'series.max' => 'Series number invalid (Must be between 1-9999 only)!',

            'memo.required' => 'Memo number required for memoranda!',
            'memo.min' => 'Memo number invalid (Must be between 1-9999 only)!',
            'memo.max' => 'Memo number invalid (Must be between 1-9999 only)!',

            'subject.required' => 'Document subject is required!',

            'files.required' => 'Softcopy file is required!',
            'files.*.mimes' => 'Softcopy file/s must be of types: '.FileExtension::getFileExtensions(),

            'category.required' => 'Document category is required!',

            'status.required' => 'Document status is required!',

            'assignee.required' => 'Assignee is required!',

            'document_date.required' => 'Date is required!',

            'description.required' => 'Description of edit is required!'
        ];
    }
}
