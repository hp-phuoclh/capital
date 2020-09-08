@foreach ($notifications as $notify)
    @php
        $types = Helper::notificationTypes();
    @endphp
    @if ($notify->type == $types['orderCreate'])
        @include('templates.order_new', ['notify' => $notify])
    @endif
@endforeach