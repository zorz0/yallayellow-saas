@if(count($filter_country) > 0)
    @foreach($filter_country as $state)
        <tr>
            <td>{{ $state->name }}</td>
            <td class="text-center">{{ $state->country->name }}</td>
            <td class="text-end">
                <button class="btn btn-sm btn-primary me-2"
                    data-url="{{ route('state.edit', $state['id']) }}"
                    data-size="md" data-ajax-popup="true"
                    data-title="{{ __('Edit State') }}">
                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="edit"></i>
                </button>

                {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['state.destroy', $state['id']],
                    'class' => 'd-inline',
                ]) !!}
                <button type="button" class="btn btn-sm btn-danger show_confirm">
                    <i class="ti ti-trash text-white py-1"
                        data-bs-toggle="tooltip" title="Delete"></i>
                </button>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
@else
    <td class="dataTables-empty" colspan="2">No entries found</td>
@endif
