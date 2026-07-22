@extends('admin.layouts.app')

@section('title', 'Inventory Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Inventory Categories</h4>
    <a href="{{ route('admin.inventory.category-create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Category
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="60">ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Parent</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td><code>{{ $category->code }}</code></td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                            @if($category->children->count())
                                <br><small class="text-muted">{{ $category->children->count() }} subcategories</small>
                            @endif
                        </td>
                        <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                        <td>{{ $category->parent->name ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $category->items_count ?? 0 }}</span></td>
                        <td>
                            <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($category->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.inventory.category-edit', $category->id) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.inventory.category-destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @foreach($category->children as $child)
                        <tr class="table-light">
                            <td></td>
                            <td><code>{{ $child->code }}</code></td>
                            <td>&nbsp;&nbsp;|-- {{ $child->name }}</td>
                            <td>{{ Str::limit($child->description, 50) ?? '-' }}</td>
                            <td>{{ $category->name }}</td>
                            <td><span class="badge bg-info">{{ $child->items_count ?? 0 }}</span></td>
                            <td>
                                <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($child->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.inventory.category-edit', $child->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.inventory.category-destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
