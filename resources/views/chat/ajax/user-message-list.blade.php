
  @if($notifyMessage->count()>0)
    @foreach($notifyMessage as $message)
      @if($message->from_id==Auth::user()->id)
          <li class="clearfix odd" id="{{$message->id}}_message">
        @else
          <li class="clearfix" id="{{$message->id}}_message">
        @endif
          <div class="chat-avatar"><span class="avatar-title bg-soft-secondary text-secondary font-10 rounded-circle">
          {{logoText($message->fromMessage->firstname).logoText($message->fromMessage->lastname)}}</span><i>{{setChatDate($message->created_at)}}</i></div>
          <div class="conversation-text"><div class="ctext-wrap"> <i>{{$message->fromMessage->firstname.' '.logoText($message->fromMessage->lastname)}}</i><p id="{{$message->id}}_currentMessage">{{$message->message}}</p></div></div>
        @if($message->from_id==Auth::user()->id)
          <div class="conversation-actions dropdown">
              <button class="btn btn-sm btn-link" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical font-16"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);" onclick="actionModel(this,{{$message->id}},1)">Edit</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="actionMessage(this,{{$message->id}},0)">Delete</a>
              </div>
            </div>
        @endif
      </li>
    @endforeach
  @else
    <!-- <h3 class="text-center">There is no conversation between both of you.</h3> -->
  @endif
  <input type="hidden" id="selected_recevier" value="{{$user_id}}"/>
  <input type="hidden" id="max_count" value="{{$max_count}}"/>