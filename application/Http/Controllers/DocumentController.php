<?php


namespace Intranet\Http\Controllers;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Contracts\Auth\AuthInterface;
use Intranet\Contracts\Repositories\DocumentRepositoryInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;

class DocumentController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var AuthInterface
     */
    private $auth;

    /**
     * DocumentController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param DocumentRepositoryInterface $documentRepository
     * @param AuthInterface $auth
     */
    public function __construct(UserRepositoryInterface $userRepository, DocumentRepositoryInterface $documentRepository, AuthInterface $auth)
    {
        $this->userRepository = $userRepository;
        $this->documentRepository = $documentRepository;
        $this->auth = $auth;
    }

    /**
     * Create a new document.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function create(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'doc_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'name' => [
                'required',
                'max_length' => [
                    'max' => 255
                ]
            ],
            'user_username' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $file = $request->file('file');

        if (!$file) {
            return \jsonError(422, ['file' => [\text('app.document.file_not_given')]]);
        }

        $targetDir = \path('upload');
        $targetFile = $targetDir . '/' . basename($file["name"]);

        if (file_exists($targetFile)) {
            return \jsonError(422, ['file' => [\text('app.document.file_already_exists', ['name' => basename($file["name"])])]]);
        }

        move_uploaded_file($file["tmp_name"], $targetFile);

        $document = [
            'name' => $request->name,
            'user_username' => $request->user_username,
            'src' => $targetFile
        ];

        $document = $this->documentRepository->save($document);

        return \json(['document' => $document, 'message' => \text('app.document.create.success', ['name' => $document['name']])]);
    }

    /**
     * Update existing document.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function update(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'doc_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'id' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.document'),
                    'column' => 'id'
                ]
            ],
            'name' => [
                'max_length' => [
                    'max' => 255
                ]
            ],
            'user_username' => [
                'exists' => [
                    'table' => \config('database.tables.user'),
                    'column' => 'username'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $document = $this->documentRepository->findById($request->id);

        $document['name'] = \is_null($request->name) ? $document['name'] : $request->name;
        $document['user_username'] = \is_null($request->user_username) ? $document['user_username'] : $request->user_username;

        $document = $this->documentRepository->save($document);

        return \json(['document' => $document, 'message' => \text('app.document.update.success')]);
    }

    /**
     * Delete the document.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function delete(RequestInterface $request)
    {
        if (!$this->userRepository->hasPermission($this->auth->user()['username'], 'doc_manage')) {
            return \jsonError(403, ['permission' => [\text('base.permission_denied')]]);
        }

        $valid = $request->validate([
            'id' => [
                'required',
                'exists' => [
                    'table' => \config('database.tables.document'),
                    'column' => 'id'
                ]
            ]
        ]);

        if (!$valid) {
            $errors = $request->errors();
            return \jsonError(422, $errors);
        }

        $document = $this->documentRepository->findById($request->id);

        \unlink($document['src']);

        $this->documentRepository->delete($request->id);

        return \json(['message' => \text('app.document.delete.success',  ['name' => $document['name']])]);
    }

    /**
     * Show page with listed cars.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function list(RequestInterface $request)
    {
        $documents = $this->documentRepository->findAll(['name']);
        $users = $this->userRepository->findAll(['username']);

        foreach ($documents as $key => $doc) {
            $documents[$key]['filename'] = \basename($doc['src']);
        }

        return \html('documents', ['documents' => $documents, 'users' => $users]);
    }

    /**
     * Show table with listed cars.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function documentsTable(RequestInterface $request)
    {
        $documents = $this->documentRepository->findAll(['name']);

        foreach ($documents as $key => $doc) {
            $documents[$key]['filename'] = \basename($doc['src']);
        }

        return \html('components.documents_table', ['documents' => $documents]);
    }
}