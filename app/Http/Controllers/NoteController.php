<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * CRUD Controller for Notes
 */
class NoteController extends Controller
{

    /**
     * Retrieve a list of Notes
     *
     * Request:
     *      GET /api/notes
     * Response example:
     *      HTTP 200
     *      {success: true, results: [{id: 1, content: "Pickup milk."}]}
     */
    public function index()
    {
        return $this->success(Note::all());
    }

    /**
     * Create a new Note
     *
     * Request:
     *      POST /api/notes
     *      content: "Pickup milk."
     * Response example:
     *      HTTP 201
     *      Location: http://localhost:8000/api/notes/1
     *      {success: true, results: [{id: 1, content: "Pickup Milk"}]}
     * Error example
     *      HTTP 400
     *      {success: false, errors: [{"content must be less than 255 characters"}]}
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return $this->failure($validator);
        }

        //safeguarded by fillable array
        $note = Note::create($request->input());

        return $this->success($note)
            ->setStatusCode(201)
            ->header('Location', action('NoteController@show', ['id' => $note->id]));
    }

    /**
     * Display the Note specified by a given id
     *
     * Request:
     *      GET /api/notes/3
     * Response example:
     *      HTTP 200
     *      {success: true, results: [{id: 3, content: "Go to store."}]}
     * Error example 1
     *      HTTP 404
     */
    public function show(Note $note)
    {
        return $this->success($note);
    }

    /**
     * Update the Note specified by id
     * Request:
     *      PUT /api/notes/1 or PATCH /api/notes/3
     *      Content-Disposition: x-www-form-urlencoded
     *      content: "Go to the other store."
     * Response example:
     *      HTTP 200
     *      {success: true, results: [{id: 3, content: "Go to the other store."}]}
     * Error example
     *      HTTP 404
     * Error example 2
     *      HTTP 400
     *      {success: false, errors: [{"content must be less than 255 characters"}]}
     */
    public function update(Request $request, Note $note)
    {
        $method = $request->getMethod();
        $input = $request->all();

        if ($method === 'PUT') {
            $validator = Validator::make($input, [
                'content' => 'required|max:255'
            ]);

            if ($validator->fails()) {
                return $this->failure($validator);
            }
        } else if ($method === 'PATCH') {  //Validated without "required" for PATCH
            $validator = Validator::make($input, [
                'content' => 'max:255'
            ]);

            if ($validator->fails()) {
                return $this->failure($validator);
            }
        }

        $note->update($request->all());

        return $this->success($note);
    }

    /**
     * Destroy the Note specified by id
     *
     * Request:
     *      DELETE /api/notes/1
     * Response example:
     *      HTTP 200
     *      {success: true}
     * Error example
     *      HTTP 404
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return $this->success();
    }

    /**
     * Wrapper for successful responses
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($results = null)
    {
        $data = ['success' => true];

        if ($results !== null) {
            if (!is_array($results)) {
                $results = [$results];
            }

            $data['results'] = $results;
        }

        return response()->json($data);
    }


    /**
     * Wrapper for Bad Request (400) response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function failure($validator)
    {
        $data = [
            'success' => false,
            'errors' => $validator->errors(),
        ];

        return response()->json($data)
            ->setStatusCode(400);
    }

}
