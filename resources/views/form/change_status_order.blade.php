@if(count($order->workflow_transitions()) > 0)
<form class="d-inline form_confirm_action" action="{{ route('orders.update', ['order'=> $order->id]) }}" method="POST"
  data-message="{{__('Would you like to change the status of ')}} {{ $order->code }}?">
  @csrf
  @method('PUT')
  <input name="mode" value="status" type="hidden">
  <select name="status" class="form-control" style="min-width: 146px;">
    @foreach ($status as $key => $st)
    @if ($key == old('status', $order->status))
    <option value="{{ $key }}" selected>{{ $st }}</option>
    @elseif($order->workflow_can($status_wf[$key]))
    <option value="{{ $key }}">{{ $st }}</option>
    @endif
    @endforeach
  </select>
</form>
@endif
@push('scripts')
<script>
  $(function () {
    $("select[name='status']").on('change', function(){
      $(this).parents('form').submit();
    });
  })
</script>
@endpush