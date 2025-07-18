<?php

namespace App\Http\Controllers\Blog\Admin;

//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;

class CategoryController extends BaseController
{
  /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$paginator = BlogCategory::paginate(5);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
        return view('blog.admin.categories.index', compact('paginator'));
      //dd(__METHOD__);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $item = new BlogCategory();
        $categoryList =$this->blogCategoryRepository->getForComboBox(); // BlogCategory::all();

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
      //dd(__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input(); //отримаємо масив даних, які надійшли з форми


        $item = (new BlogCategory())->create($data); //створюємо об'єкт і додаємо в БД

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.edit', [$item->id])
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
     //dd(__METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
     //dd(__METHOD__);
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id)
        {
            $item = $this->blogCategoryRepository->getEdit($id);
            if (empty($item)) {                         //помилка, якщо репозиторій не знайде наш ід
                abort(404);
            }
            $categoryList = $this->blogCategoryRepository->getForComboBox($item->parent_id);

            return view('blog.admin.categories.edit', compact('item', 'categoryList'));
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
            $item =$this->blogCategoryRepository->getEdit($id); // BlogCategory::find($id);
            if (empty($item)) { //якщо ід не знайдено
                return back() //redirect back
                    ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"]) //видати помилку
                    ->withInput(); //повернути дані
            }

            $data = $request->all(); //отримаємо масив даних, які надійшли з форми

            $result = $item->update($data);  //оновлюємо дані об'єкта і зберігаємо в БД

            if ($result) {
                return redirect()
                    ->route('blog.admin.categories.edit', $item->id)
                    ->with(['success' => 'Успішно збережено']);
            } else {
                return back()
                    ->with(['msg' => 'Помилка збереження'])
                    ->withInput();
            }
     //dd(__METHOD__);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
