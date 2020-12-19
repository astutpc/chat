@extends('layouts.master')
@section('custom-css')
<link href="{{ URL::asset('css/chat.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
    .chat-body-main
    {
        position:relative;
    }
    .load-chat-spinner
    {
        position:absolute;
        top:10px;
        left:50%;
        transform:translateY(-50%); 
                            
    }
    .load-chat-spinner i
    {
        font-size:28px;
    }
    /* .current_user_option
    {
        position:absolute;
        top:0;
    } */
</style>
@endsection
@section('meta_page_title','Chat | Tenantden')
@section('content')

<div class="content mt-5 chat-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media mb-3">
                            <div class="avatar-md">
                                <span class="avatar-title bg-soft-secondary text-secondary font-20 rounded-circle">
                                    AK
                                </span>
                            </div>
                            <div class="media-body m-2">
                                <h5 class="mt-0 mb-0 font-15">
                                    <span class="text-reset">{{Auth::user()->name}}</span>
                                </h5>
                                <p class="mt-1 mb-0 text-muted font-14">
                                    <small class="mdi mdi-circle text-success"></small> Online
                                </p>    
                            </div>
                        </div>
                        <!-- start search box -->
                        {{ Form::select('user_name', $userList, '', array('class'=>'selectpicker mb-3','placeholder'=>'Select People', 'id'=> 'user_name', 'data-live-search'=>'true', 'data-size'=>5))  }}
                        <!-- users -->
                        <h6 class="font-13 text-muted text-uppercase mb-2">Contacts</h6>
                        <div class="row">
                            <div class="col">
                                <div class="user-chat-list" data-simplebar style="max-height: 375px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-body py-2 px-3 border-bottom border-light">
                        <div class="media py-1">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-secondary text-secondary font-10 rounded-circle current-reciever-logo-text d-none">
                                    
                                </span>
                            </div> 
                            <div class="media-body m-1">
                                <h5 class="mt-0 mb-0 font-15">
                                    <a href="#" class="text-reset current-reciever"></a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body chat-body-main">
                        <h3 class="text-center" id="default_text">Select user which you want to send message!</h3>
                        <div class="load-chat-spinner d-none">
                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <ul class="conversation-list" id="conversation-list-scroll" data-simplebar-auto-hide="false" data-simplebar style="max-height: 460px">
                                           
                        </ul>
                        <input type="hidden" id="page_start" value="1"/>
                        <div class="row" id="chat-section">
                            <div class="col">
                                <div class="mt-2 bg-light p-3 rounded">
                                    <div class="row">
                                        <div class="col mb-2 mb-sm-0">
                                            <input type="text" class="form-control border-0" id="message_text" placeholder="Enter your text" required="">
                                            <div class="invalid-feedback">
                                                Please enter your messsage
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <div class="btn-group">
                                                <button type="button" id="send_message" onclick="sendMessage()" class="btn btn-success chat-send btn-block" data-style="slide-down"><i class='fe-send'></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="standard-modal" class="modal doc-modal fade application-modal" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content chat-modal">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">User Message!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Edit your Message.</p>
                <div class="form-group doc-group col-md-12">
                    <input type="hidden" name="message_edit_id" id="message_edit_id">
                    <input type="text" name="message_text" id="message_text_model"/>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-submit"  id="send_edit_message" data-style="slide-down" onclick="actionMessage(this,'1','1')"  data-style="slide-down">Submit</button>
            </div>
        </div>
    </div>
 </div> 
@endsection
@section('plugin-script')

@endsection

@section('custom-script')
<script>
    window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
</script>
<script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>
<script src="{{ url('/js/app.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    
       
      
    </script>
<script>
    
    $(document).ready(function() {
        window.Echo.join('message.{{Auth::user()->id}}').
            here((users) => {
                alert("Hello");
            })
            .joining((user) => {
                alert("join");
            })
            .leaving((user) => {
                alert("leave");
        }).listen('SendMessage', (e) => {
          alert("ss");
      });
        $('#user_name').change(function() {
            var user_id = $(this).val();
            var name = $("#user_name option:selected").text();
            var matches = name.match(/\b(\w)/g);
            var logo_text = matches.join('');
            
            $('.current-reciever-logo-text').removeClass('d-none');
            $('.current-reciever').html(name);
            $('.current-reciever-logo-text').html(logo_text);
            
            var list = 0;
            var page =1;
            $('#page_start').val(1);
            getMessage(user_id,list,page);
        })

      //  userChatList();
        var didScroll = false;
        var receiver_id = '';
        
        // var pusher = new Pusher('b0a0032541e87df7f6b0', {
        //     cluster: 'ap2',
        //     forceTLS: true,
        //     encrypted: true ,

       
        // });
        // var channel = pusher.subscribe('my-channel');
        // channel.bind('my-event', function (data) {
        //     //userChatList();
        //     if (my_id == data.from) {
        //         console.log("you are in sender section");
        //         var me_click = $('#selected_recevier').val();
        //         if(me_click==data.to) 
        //         {
        //             appendLastMessageStatic(data);
        //            //appendLastMessage(data.to,sender=true);
        //         }
        //     } 
        //     else if (my_id == data.to) {
        //         console.log("you are in receiver section");
        //         // $('#'+me_click+'_pending').click();
        //         var me_click = $('#selected_recevier').val();
        //         if (me_click == data.from)
        //         {
        //             appendLastMessageStatic(data);
        //         } 
        //         else
        //         {
        //             var pending = parseInt($('#'+data.from+'_pending').text());
        //             if ($('#'+data.from+'_pending').length) {   
        //                 $('#'+data.from+'_pending').text(pending + 1);
        //             }
        //             else{
        //                 $('#'+data.from+'_pending').text('0');
        //             }
        //             updateLstMsgUserList(data.from,data.last_short_message,change_position=true);
        //         }
        //     }
        // });
        
        // var presence = pusher.subscribe('presence-user');
        // presence.bind('pusher:subscription_succeeded', function(members) {
        //     members.each(function(member) {
        //         //console.log('member subsribed',member.id);
        //         $('#is_online_'+member.id).removeClass('text-danger');
        //         $('#is_online_'+member.id).addClass('text-success');
        //     });
        // });

        // presence.bind('pusher:member_added', function(member) {
        //     // /console.log('member added',member.id);
        //     $('#is_online_'+member.id).removeClass('text-danger');
        //     $('#is_online_'+member.id).addClass('text-success');
        // });

        // presence.bind('pusher:member_removed', function(member) {
        //     //console.log('member removed',member.id);
        //     $('#is_online_'+member.id).addClass('text-danger');
        //     $('#is_online_'+member.id).removeClass('text-success');
        // });

        // presence.bind('pusher:subscription_error', function(data) {
        //     console.log("pus",data);
        // });
        $('#conversation-list-scroll .simplebar-content-wrapper').on('scroll',function(e){
            didScroll = true;
        });
        setInterval(function() {
            if (didScroll){
               didScroll = false;
               var scroll_position = $('#conversation-list-scroll .simplebar-content-wrapper').scrollTop();
               if(scroll_position <= 10)
               {
                   pageCountUpdate(); 
               }
            }
        }, 2000);
    });
    function appendLastMessageStatic(data=Null)
    {
        var my_id = "{{ Auth::id() }}";
        var last_message = '';
        if(my_id==data.from)
        {
            last_message+='<li class="clearfix odd" id="'+data.message_id+'_message">'
        }
        else
        {
            last_message+='<li class="clearfix" id="'+data.message_id+'_message">'
        }
        last_message+='<div class="chat-avatar"><span class="avatar-title bg-soft-secondary text-secondary font-10 rounded-circle">'
             +data.logo_text+'</span><i>'+data.chat_date+'</i></div>'
             +'<div class="conversation-text"><div class="ctext-wrap"> <i>'+data.heading_message_text+'</i><p id="'+data.message_id+'_currentMessage">'+data.last_message+'</p></div></div>'
            if(my_id==data.from)
            {
              last_message+='<div class="conversation-actions dropdown">'
               +'<button class="btn btn-sm btn-link" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical font-16"></i></button>'
               +'<div class="dropdown-menu">'
               +'<a class="dropdown-item" href="javascript:void(0);" onclick="actionModel(this,'+data.message_id+',1)">Edit</a>'
               +'<a class="dropdown-item" href="javascript:void(0)" onclick="actionMessage(this,'+data.message_id+',0)">Delete</a>'
               +'</div>'
               +'</div>'
            }    
        last_message+='</li>'
        $('.conversation-list .simplebar-content').append(last_message);
        $('#conversation-list-scroll .simplebar-content-wrapper').scrollTop($('#conversation-list-scroll .simplebar-content-wrapper').find('.simplebar-content').height());
        var user_message_count = parseInt($('#'+data.to+'_pending').text());
        var current_message_count = parseInt($('#'+my_id+'_current_pending').text());
        $('#'+data.to+'_pending').text('0');
        var user_receiver;
        if(data.from==my_id)
        {
            user_receiver = data.to;
        }
        else
        {
            user_receiver = data.from;
        }
        updateLstMsgUserList(user_receiver,data.last_short_message,change_position=false);
        var pending_message = current_message_count-user_message_count;
        if(pending_message>0)
        {
            $('#'+my_id+'_current_pending').text(pending_message);
        }
        else
        {
            $('#'+my_id+'_current_pending').text('0');
        }
        
        
    }
    function updateLstMsgUserList(user_receiver=Null,message=Null,change_position=Null)
    {
        $('.'+user_receiver+'_user_last_message').text(message);
        if(change_position)
        {
            $('.'+user_receiver+'_user_change_message').clone().hide().prependTo('.user-chat-list .simplebar-content').slideDown();
            $('.'+user_receiver+'_user_change_message:last').remove();
        }
    }
    function appendLastMessage(user_id=Null,sender=Null)
    {
        if(user_id!=='') {
        var my_id = "{{ Auth::id() }}";
                $.ajax({
                type: 'get',
                url: "{{route('chat.appendLastMessage')}}",
                data:  {user_id:user_id},
                dataType: "json",
                beforeSend:function(e)
                {
                    
                },
                success: function(resultData) {
                    if(resultData.success)
                    {
                            $('.conversation-list .simplebar-content').append(resultData.html);
                            $('#conversation-list-scroll .simplebar-content-wrapper').scrollTop($('#conversation-list-scroll .simplebar-content-wrapper').find('.simplebar-content').height());
                            var user_message_count = parseInt($('#'+user_id+'_pending').text());
                            var current_message = parseInt($('#'+my_id+'_current_pending').text());
                            $('#'+user_id+'_pending').text('0');
                            var pending_message = current_message-user_message_count;
                            if(pending_message>0)
                            {
                                console.log('total_message',user_message_count);
                                console.log('your message',current_message);
                                $('#'+my_id+'_current_pending').text(pending_message);
                            }
                            else{
                                $('#'+my_id+'_current_pending').text('0');
                            }
                            
                    //    }
                        
                    }
                    else if(!resultData.success)
                    {
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    }
                    else if (jqXHR.status == 401) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    
                    $.toast({
                        heading: 'Error',
                        text: msg,
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    })
                }
            });
        }
    }

    function pageCountUpdate(){
        var page = parseInt($('#page_start').val());
        var max_page = parseInt($('#max_count').val());
       
        if(page < max_page){
           $('#page_start').val(page+1);
           var user_id = $('#selected_recevier').val();
           var list = 2;
           var next_page = $('#page_start').val();
           getMessage(user_id,list,next_page);
        }
        else
        {
          return false;
        }
    }
    
    $('body').on('click', '.receiver', function(e) { 
       var user_id = $(this).attr('id');
       var list = 1;
       var page = 1;
       $('#page_start').val(1)
       getMessage(user_id,list,page);
    });
    
    function sendMessage()
    {
      //  alert("jik");
        var message_text = $('#message_text').val();
        $('#message_text').val('');
        receiver_id =  '2';
        if ($.trim(message_text) !='')
        {
            $.ajax({
                type: 'POST',
                url: "{{route('chat.sendMessage')}}",
                data:  {user_id:receiver_id,message_text:message_text},
                dataType: "json",
                beforeSend: function() {
                    l = Ladda.create( document.querySelector('.chat-send') );
                    l.start();
                },
                success: function(resultData) {
                    l.stop();
                    if(resultData.success)
                    {
                        //getMessage(user_id,list=1);
                        // $('.conversation-list').html(resultData.html);
                    }
                    else if(!resultData.success)
                    {
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    }
                    else if (jqXHR.status == 401) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    l.stop();
                    $.toast({
                        heading: 'Error',
                        text: msg,
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    })
                }
            });
        }
    }
    
   
    function getMessage(user_id=Null,list=NULL,page=Null)
    {
        if(user_id!=='') {
            var my_id = "{{ Auth::id() }}";
            $.ajax({
                type: 'get',
                url: "{{route('chat.fetchMessages')}}",
                data:  {page:page,user_id:user_id},
                dataType: "json",
                beforeSend:function(e)
                {
                    if(list==2)
                    {
                        $('.load-chat-spinner').removeClass('d-none');
                    }
                },
                success: function(resultData) {
                    if(resultData.success)
                    {
                         $('#default_text').addClass('d-none');
                         $('#chat-section').removeClass('d-none');
                        if(list==1)
                        {
                            if(resultData.max_count==0)
                            {
                                $('.current-reciever-logo-text').addClass('d-none');
                            }
                            else {
                                $('.current-reciever-logo-text').removeClass('d-none');
                            }
                            $('.current-reciever').html(resultData.reciever_name);
                            $('.current-reciever-logo-text').html(resultData.reciever_logo_text);
                        }
                        var user_message_count = parseInt($('#'+user_id+'_pending').text());
                        var current_message = parseInt($('#'+my_id+'_current_pending').text());
                        $('#'+user_id+'_pending').text('0');
                       
                        var pending_message = current_message-user_message_count;
                        if(pending_message>0)
                        {
                           $('#'+my_id+'_current_pending').text(pending_message);
                        }
                        else{
                            $('#'+my_id+'_current_pending').text('0');
                        }
                            
                        if(list==2)
                        {
                            $('.load-chat-spinner').addClass('d-none');
                            $('.conversation-list .simplebar-content').prepend(resultData.html);
                        }
                        else{
                            $('.conversation-list .simplebar-content').html(resultData.html);
                            
                        }
                         var container = document.querySelector('#conversation-list-scroll .simplebar-content-wrapper'); 
                         container.scrollTo({ top: 2500, behavior: "smooth" });
                    }
                    else if(!resultData.success)
                    {
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    }
                    else if (jqXHR.status == 401) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                     $('.load-chat-spinner').addClass('d-none');
                    $.toast({
                        heading: 'Error',
                        text: msg,
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    })
                }
            });
        }
    }
 
    function actionMessage(data=null,id=null,status=null) 
    {
        if(id!=='' && status!=='')
        {
            var message = ''
            if(status==1)
            {
                var l;
                l = Ladda.create(document.querySelector('.btn-submit'));
                l.start();
                message = $('#message_text_model').val();
                id = $('#message_edit_id').val();
                if(message=='')
                {
                    l.stop();
                    $('#standard-modal').modal('toggle');
                    return false;
                }
            }  
            $.ajax({
                type: 'POST',
                url: "{{route('chat.message.action')}}",
                data:  {message_id:id,status:status,message:message},
                dataType: "json",

                success: function(resultData) {
                    if(resultData.success)
                    {
                        if(resultData.status=='deleted')
                        {
                            $('#'+id+'_message').remove();
                        }
                        else if(resultData.status=='edit')
                        {
                            $('#'+id+'_currentMessage').text(message);
                            l.stop();
                            $('#standard-modal').modal('toggle');
                        }
                    }
                    else if(!resultData.success)
                    {
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    }
                    else if (jqXHR.status == 401) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 419) {
                        window.location.reload();
                    }
                    else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    l.stop();
                    $.toast({
                        heading: 'Error',
                        text: msg,
                        icon: 'error',
                        position: 'top-right',
                        loader: false,
                    })
                }
            });
        }
    }
   
    function userChatList() 
    {
        $.ajax({
            type: 'POST',
            url: "{{route('user.chat.list')}}",
            dataType: "json",
            success: function(resultData) {
                if(resultData.success)
                {
                    //getMessage(user_id,list=1);
                    if(resultData.user_list_count < 1)
                    {
                        $('.user-chat-list .simplebar-content').html('<p>There is no previous chat yet.</p>');
                        return false;
                    }
                    $('.user-chat-list .simplebar-content').html(resultData.html);
                    // var pusher = new Pusher('b0a0032541e87df7f6b0', {
                    //     cluster: 'ap2',
                    //     forceTLS: true,
                    //     encrypted: true ,
                        
                    
                    // });
                   var presence = pusher.subscribe('presence-user');
                       presence.bind('pusher:subscription_succeeded', function(members) {
                        members.each(function(member) {
                    //console.log('member subsribed',member.id);
                                $('#is_online_'+member.id).removeClass('text-danger');
                                $('#is_online_'+member.id).addClass('text-success');
                            });
                    });

                }
                else if(!resultData.success)
                {
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                }
                else if (jqXHR.status == 401) {
                    window.location.reload();
                }
                else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $.toast({
                    heading: 'Error',
                    text: msg,
                    icon: 'error',
                    position: 'top-right',
                    loader: false,
                })
            }
        });
    }
    function actionModel(data=null,id=null,status=null)
    {
        var text = $('#'+id+'_currentMessage').text();
        $('#message_text_model').val(text);
        $('#message_edit_id').val(id);
        $('#standard-modal').modal('show');
    }
    //execue send message
    var message = document.getElementById("message_text");
    
    message.addEventListener("keydown", function (e) {
        if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
            $('#send_message').click();
        }
    });

 
</script>  

@endsection