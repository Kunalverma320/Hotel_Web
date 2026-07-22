@extends('admin.layouts.app')

@section('title', 'Menu Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-book"></i> Menu Management</h4>
</div>

<ul class="nav nav-tabs mb-4" id="menuTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button">
            <i class="bi bi-folder"></i> Categories
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="items-tab" data-bs-toggle="tab" data-bs-target="#items" type="button">
            <i class="bi bi-egg-fried"></i> Food Items
        </button>
    </li>
</ul>

<div class="tab-content" id="menuTabContent">
    <div class="tab-pane fade show active" id="categories" role="tabpanel">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="bi bi-plus-lg"></i> Add Category
            </button>
        </div>

        <div class="row g-3">
            @forelse($categories as $category)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1">{{ $category->name }}</h5>
                                    <p class="text-muted mb-1">{{ $category->description ?? 'No description' }}</p>
                                    <small class="text-muted">{{ $category->foodItems->count() ?? 0 }} items</small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.restaurant.menu-category-destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item text-danger"><i class="bi bi-trash"></i> Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-3">
                                @foreach($category->foodItems->take(3) as $item)
                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $item->name }} - ${{ number_format($item->price, 2) }}</span>
                                @endforeach
                                @if(($category->foodItems->count() ?? 0) > 3)
                                    <span class="badge bg-secondary">+{{ ($category->foodItems->count() ?? 0) - 3 }} more</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.restaurant.menu-category-update', $category->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control">{{ $category->description }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sort Order</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ $category->sort_order }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-4 text-muted">No categories yet. Add your first menu category.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="tab-pane fade" id="items" role="tabpanel">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal">
                <i class="bi bi-plus-lg"></i> Add Food Item
            </button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories->flatMap->foodItems as $item)
                                <tr>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td><span class="badge bg-light text-dark">{{ $item->category->name ?? 'N/A' }}</span></td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>
                                        @if($item->is_available)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editItem{{ $item->id }}"><i class="bi bi-pencil"></i></button>
                                            <form action="{{ route('admin.restaurant.food-item-destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No food items found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.restaurant.menu-category-store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.restaurant.food-item-store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Food Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_available" value="1" checked id="isAvailable">
                        <label class="form-check-label" for="isAvailable">Available</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
