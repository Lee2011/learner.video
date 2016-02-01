<?php

namespace Learner\Http\Controllers\Admin;

use Log;
use Learner\Repositories\BlogRepositoryInterface;
use Learner\Http\Controllers\Admin\BaseController;

class BlogController extends BaseController
{
    /**
     * Blog repository.
     *
     * @var \Learner\Repositories\BlogRepositoryInterface
     */
    protected $blogs;

    /**
     * Instance blog repository.
     *
     * @param \Learner\Repositories\BlogRepositoryInterface $blogs
     */
    public function __construct(BlogRepositoryInterface $blogs)
    {
        $this->blogs = $blogs;
    }

    /**
     * Get all blogs with relation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $blogs = $this->blogs->findAllWithRelation();

        return $this->responseJson(compact('blogs'));
    }

    /**
     * Save created blog.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $form = $this->blogs->getCreateForm();

        if (! $form->isValid()) {
            return $this->responseJson(['errors' => $form->getErrors()], 400);
        }

        $formData = $form->getInputData();

        if ($formData['created_at'] == '') {
            unset($formData['created_at']);
        }

        $this->blogs->create($formData);

        return $this->responseJson(['message' => '添加成功']);
    }

    /**
     * Edit the blog.
     *
     * @param  integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $blog = $this->blogs->findById($id);

        return $this->responseJson(compact('blog'));
    }

    /**
     * Update the blog.
     *
     * @param  integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $form = $this->blogs->getUpdateForm();

        if (! $form->isValid()) {
            return $this->responseJson(['errors' => $form->getErrors()], 400);
        }

        $this->blogs->update($id, $form->getInputData());

        return $this->responseJson(['message' => '修改成功']);
    }

    /**
     * Delete a video.
     *
     * @param  integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destory($id)
    {
        if ($this->blogs->delete($id)) {
            Log::info(lang('log.deleteBlogSuccess', 'Delete a blog.'));

            return $this->responseJson(['message' => '删除成功']);
        } else {
            return $this->responseJson(['error' => '出现未知问题']);
        }
    }

    /**
     * Toggle blog published.
     *
     * @param  integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePublished($id)
    {
        if ($this->blogs->togglePublished($id)) {
            $message = '博客发布成功';
        } else {
            $message = '博客已保存';
        }

        return $this->responseJson(compact('message'));
    }

    /**
     * Toggle blog toped.
     *
     * @param  integer $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleTop($id)
    {
        if ($this->blogs->toggleTop($id)) {
            $message = '博客顶置成功';
        } else {
            $message = '博客取消顶置';
        }

        return $this->responseJson(compact('message'));
    }
}
