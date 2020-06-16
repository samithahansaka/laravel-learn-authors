<?php

namespace App\Http\Controllers;

use App\Author;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthorController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return the list of authors
     * @return JsonResponse
     */
    public function index(){
        $authors = Author::all();
        return $this->successResponse($authors);
    }

    /**
     * Create a new author
     * @param Request $request
     * @throws ValidationException
     * @return JsonResponse
     */
    public function store(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'gender' => 'required|max:255|in:male,female',
            'country' => 'required|max:255'
        ];

        $this->validate($request, $rules);

        $author = Author::create($request->all());

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * Get one author
     * @param $author
     * @return JsonResponse
     */
    public function show($author){
        $author = Author::findOrFail($author);
        return $this->successResponse($author);
    }

    /**
     * Update specific author
     * @param Request $request
     * @param $author
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $author){
        $rules = [
            'name' => 'max:255',
            'gender' => 'max:255|in:male,female',
            'country' => 'max:255'
        ];

        $this->validate($request, $rules);

        $author = Author::findOrFail($author);

        $author->fill($request->all());

        if($author->isClean()){
            return $this->errorResponse('Nothing to update', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $author->save();

        return $this->successResponse($author);
    }

    /**
     * @param $author
     * @return JsonResponse
     */
    public function destroy($author){
        $author = Author::findOrFail($author);

        $author->delete();

        return $this->successResponse($author);
    }
}
