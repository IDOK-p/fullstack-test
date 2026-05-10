<?php

namespace App\Controllers;

use App\Models\CommentModel;

class Comments extends BaseController
{
    public function index()
    {
        return view('comments');
    }

    public function list()
    {
        $model = new CommentModel();

        $page = $this->request->getGet('page') ?? 1;
        $sort = $this->request->getGet('sort') ?? 'id';
        $order = $this->request->getGet('order') ?? 'DESC';

        $perPage = 3;
        $offset = ($page - 1) * $perPage;

        $comments = $model
            ->orderBy($sort, $order)
            ->findAll($perPage, $offset);

        $total = $model->countAll();

        return $this->response->setJSON([
            'data' => $comments,
            'total' => $total
        ]);
    }

    public function add()
    {
        $model = new CommentModel();

        $email = $this->request->getPost('name');
        $text = $this->request->getPost('text');
        $date = $this->request->getPost('date');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['error' => 'Invalid email']);
        }

        $model->save([
            'name' => $email,
            'text' => $text,
            'date' => $date
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function delete($id)
    {
        $model = new CommentModel();
        $model->delete($id);

        return $this->response->setJSON(['success' => true]);
    }
}
