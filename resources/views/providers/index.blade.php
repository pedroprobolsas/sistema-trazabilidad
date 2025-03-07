@empty
                            <tr>
                                <td colspan="6" class="text-center">No hay proveedores registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay proveedores registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay proveedores registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Proveedores</h1>
        <a href="{{ route('providers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Proveedor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>NIT</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($providers as $provider)
                            <tr>
                                <td>{{ $provider->name }}</td>
                                <td>{{ $provider->contact_name }}</td>
                                <td>{{ $provider->phone }}</td>
                                <td>{{ $provider->email }}</td>
                                <td>{{ $provider->tax_id }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('providers.show', $provider) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="{{ route('providers.edit', $provider) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('providers.destroy', $provider) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este proveedor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay proveedores registrados</td>
                            </tr>