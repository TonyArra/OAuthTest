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
     *      {"success":true,"results":[{"id":2,"created_at":"2017-02-17 19:45:01","updated_at":"2017-02-17 19:45:04","content":"test","Pickup milk.":"[\"shopping\"]"}]}
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
     *      {"success":true,"results":[{"id":2,"created_at":"2017-02-17 19:45:01","updated_at":"2017-02-17 19:45:04","content":"test","Pickup milk.":"[\"shopping\"]"}]}
     * Error example
     *      HTTP 400
     *      {success: false, errors: [{"content must be less than 255 characters"}]}
     */
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'content' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $this->failure($validator->errors());
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
     *      {"success":true,"results":[{"id":2,"created_at":"2017-02-17 19:45:01","updated_at":"2017-02-17 19:45:04","content":"test","Pickup milk.":"[\"shopping\"]"}]}
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
     *      {"success":true,"results":[{"id":2,"created_at":"2017-02-17 19:45:01","updated_at":"2017-02-17 19:45:04","content":"test","Go to the other store.":"[\"shopping\"]"}]}
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
                return $this->failure($validator->errors());
            }
        } else if ($method === 'PATCH') {  //Validated without "required" for PATCH
            $validator = Validator::make($input, [
                'content' => 'max:255'
            ]);

            if ($validator->fails()) {
                return $this->failure($validator->errors());
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
     * Tag a Note
     * Request:
     *      POST /api/notes/tags/1/shopping
     * Response example:
     *      HTTP 200
     *      {"success":true,"results":[{"id":2,"created_at":"2017-02-17 19:45:01","updated_at":"2017-02-17 19:45:04","content":"test","Pickup milk.":"[\"shopping\"]"}]}
     * Error example
     *      HTTP 404
     * Error example 2
     *      HTTP 400
     *      {success: false, errors: [{"only 5 tags are allowed per note"}]}
     */
    public function tag(Note $note, $tag)
    {
        if (strlen($tag) > 30) {
            return $this->failure(["tag must be less than 31 characters"]);
        }

        $tags = json_decode($note->tags);

        if (count($tags) >= 5 ) {
            return $this->failure(["only 5 tags are allowed per note"]);
        }

        $tags[] = $tag;
        $note->tags = json_encode($tags);
        $note->save();

        return $this->success($note);
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
    protected function failure($errors)
    {
        $data = [
            'success' => false,
            'errors' => $errors,
        ];

        return response()->json($data)
            ->setStatusCode(400);
    }

}
