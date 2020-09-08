<div class="dropdown-divider"></div>
<a href="{{ route("orders.show", ['order' => $notify->data['id'], 'read' => $notify->id]) }}" class="dropdown-item @if(isset($new) && $new) n-new @elseif(!$notify->read_at) n-unread @endif">
  <span class="n-content">
    <i class="fas fa-custom-orderCreate mr-2"></i><b>{{Helper::getUserById($notify->data['user_id'])->name}}</b> {{ __("has placed a new order.") }}
  </span>
  <span class="d-block text-muted text-sm mt-2">{!! $notify->created_at ? $notify->created_at->format('G:i d/m/Y') : '' !!}</span>
</a>