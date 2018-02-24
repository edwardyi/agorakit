@extends('app')



@include('partials.datatables')
@push('js')
    <script>
    $(document).ready(function(){
        $('.files-grid').DataTable( {
            paging: false,
            order: []
        });
    });
    </script>

@endpush



@section('content')

    @include('groups.tabs')


    <div class="tab_content">

        @include('partials.invite')



        <div class="toolbox">
            @can('create-file', $group)
                <a class="btn btn-primary" href="{{ route('groups.files.create', $group->id ) }}">
                    <i class="fa fa-file"></i>
                    {{trans('messages.create_file_button')}}
                </a>
            @endcan

            @can('create-file', $group)
                <a class="btn btn-primary" href="{{ route('groups.files.createlink', $group ) }}">
                    <i class="fa fa-link"></i>
                    {{trans('messages.create_link_button')}}
                </a>
            @endcan
        </div>



        <table class="table table-hover files-grid">

            <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('messages.name') }}</th>
                    <th>{{ trans('messages.tags') }}</th>
                    <th>{{ trans('messages.author') }}</th>
                    <th>{{ trans('messages.size') }}</th>
                    <th>{{ trans('messages.date') }}</th>
                    <th></th>
                </tr>

            </thead>

            <tbody>

                @forelse( $files as $file )
                    <tr class="file-item @foreach ($file->tags as $tag)tag-{{$tag->name}} @endforeach">
                        <td>
                            @if ($file->isLink())
                                <a href="{{ route('groups.files.download', [$group->id, $file->id]) }}" target="_blank">
                                    <i class="fa fa-link" aria-hidden="true" style="font-size:1.8rem; color: black"></i>
                                </a>
                            @else
                                <a href="{{ route('groups.files.show', [$group->id, $file->id]) }}">
                                    <img src="{{ route('groups.files.thumbnail', [$group->id, $file->id]) }}"/>
                                </a>
                            @endif
                        </td>

                        <td>
                            <div class="ellipsis" style="max-width: 30em">
                                @if ($file->isLink())
                                    <a href="{{ route('groups.files.download', [$group->id, $file->id]) }}" target="_blank">
                                        {{ $file->name }}
                                        <i class="fa fa-external-link"></i>
                                    </a>
                                @else
                                    <a  href="{{ route('groups.files.show', [$group->id, $file->id]) }}">{{ $file->name }}</a>
                                @endif
                            </div>
                        </td>



                        <td>
                            @foreach ($file->tags as $tag)
                                <span class="badge badge-secondary">{{$tag->name}}</span>
                            @endforeach
                        </td>

                        <td>
                            @unless (is_null ($file->user))
                                <a href="{{ route('users.show', $file->user->id) }}">{{ $file->user->name }}</a>
                            @endunless
                        </td>

                        <td data-order="{{ $file->filesize }}">
                            @if ($file->isFile()){{sizeForHumans($file->filesize)}}@endif
                            </td>

                            <td data-order="{{ $file->created_at }}">
                                {{$file->created_at->diffForHumans()}}
                            </td>

                            <td>
                                @can('update', $file)
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('groups.files.edit', [$group->id, $file->id]) }}"><i class="fa fa-edit"></i>
                                        {{trans('messages.edit')}}
                                    </a>
                                @endcan

                                @can('delete', $file)
                                    <a class="btn btn-link btn-sm" href="{{ route('groups.files.deleteconfirm', [$group->id, $file->id]) }}"><i class="fa fa-trash"></i>
                                        {{trans('messages.delete')}}
                                    </a>
                                @endcan

                            </td>
                        </tr>

                    @empty
                        {{trans('messages.nothing_yet')}}
                    @endforelse

                </tbody>
            </table>



        </div>


    @endsection
