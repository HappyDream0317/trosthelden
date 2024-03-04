@extends('layouts.app')

@section('title', '| Group Kategorie erstellen')

@section('content')

    <h1>Boot Kategorie erstellen</h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Beschreibung</th>
                <th></th>
                <th>Max. Größe</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach ($categories as $category)
                <tr>

                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>{{ $category->icon }}</td>
                    <td>{{ $category->max_size }}</td>
                    <td>
                        <a href="{{ URL::to('admin/bootcategories/'.$category->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                        {!! Form::open(['method' => 'DELETE', 'route' => ['admin.bootcategories.destroy', $category->id] ]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}

                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ URL::to('admin/bootcategories/create') }}" class="btn btn-success">Trostboot Kategorie hinzufügen</a>

@endsection
