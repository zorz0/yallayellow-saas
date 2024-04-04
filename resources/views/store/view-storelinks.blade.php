
<table class="table dataTable">
    <thead>
        <tr>
            <th>{{ __('Store Name') }}</th>
            <th class="text-end">{{ __('Store Links') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stores as $store)
            <tr>
                <td>{{ $store->name }}</td>
                <td class="text-end">
                    <input type="text" value="{{ route('landing_page',$store->slug) }}"
                                                id="myInput_{{$store->slug}}" class="form-control d-inline-block theme-link"
                                                aria-label="Recipient's username"
                                                aria-describedby="button-addon2" readonly>
                    <button class="btn btn-outline-primary" type="button"
                        onclick="myFunction('myInput_{{$store->slug}}')" id="button-addon2"><i
                            class="far fa-copy"></i>
                        {{ __('Store Link') }}</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script type="text/javascript">

    function myFunction(id) {
            var copyText = document.getElementById(id);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', "{{ __('Link copied') }}", 'success');
            }
</script>
