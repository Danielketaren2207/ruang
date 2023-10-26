var time = $('#remaining_time');
if(time.length){
	remaining_time(time.data('time'));
}

page(1);
save();

$('#btn-back').hide();
$('#btn-finish').hide();
$("#widget_1").show();

function not_answer()
{
  var rel = $('#btn-doubtfull').attr('rel');
  var status_doubt = $('#doubt_'+rel).val();

  if(status_doubt == 'N'){
    $('#doubt_'+rel).val('Y');
    $('#btn_'+rel).removeClass('btn-success');
    $('#btn_'+rel).addClass('btn-warning');
  }else{
    $('#doubt_'+rel).val('N');
    $('#btn_'+rel).removeClass('btn-warning');
    $('#btn_'+rel).addClass('btn-success');
  }
  save();
}

function page(number)
{
  $('#btn-next').attr('rel', (number + 1));
  $('#btn-back').attr('rel', (number - 1));
  $('#btn-doubtfull').attr('rel', number);

  $('#question_to').html(number);

  check_last(number);

  $(".step").hide();
  $('#widget_'+number).show();

  var first = (number == 1) ? 1 : 0;
  var last = (number == total_widget) ? 1 : 0;
  if(first == 1){
    $('#btn-back').hide();
    $('#btn-next').show();
  }else{
    $('#btn-back').show();
    if(last == 1){
      $('#btn-next').hide();
    }else{
      $('#btn-next').show();
    }
  }
}

function next()
{
  var next = $('#btn-next').attr('rel');
  next = parseInt(next);
  next = (next > total_widget) ? total_widget : next;

  $('#question_to').html(next);

  $('#btn-next').attr('rel', (next + 1));
  $('#btn-back').attr('rel', (next - 1));

  save();
  check_last(next);

  $(".step").hide();
  $("#widget_"+next).show();

  var last = (next == total_widget) ? 1 : 0;
  if(last == 1){
    $('#btn-back').show();
    $('#btn-next').hide();
  }else{
    $('#btn-back').show();
    $('#btn-next').show();
  }
}

function back()
{
  var back = $('#btn-back').attr('rel');
  back = parseInt(back);
  back = (back < 1) ? 1 : back;

  $('#question_to').html(back);

  $('#btn-next').attr('rel', (back + 1));
  $('#btn-back').attr('rel', (back - 1));
  $('#btn-doubtfull').attr('rel', back);

  check_last(back);

  $(".step").hide();
  $("#widget_"+back).show();

  var first = (back == 1) ? 1 : 0;
  if(first == 1){
    $('#btn-back').hide();
    $('#btn-next').show();
  }else{
    $('#btn-back').show();
    $('#btn-next').show();
  }
}

function check_last(no_question)
{
  var total_question = $('#total_question').val();
  total_question = parseInt(total_question);
  if(no_question == total_question){
    $('#btn-next').hide();
    $('#btn-back').show();
    $('#btn-finish').show();
  }else{
    $('#btn-next').show();
    $('#btn-back').hide();
    $('#btn-finish').hide();
  }
}

function save()
{
  var id_form = $('#exam');
  var form = getFormData(id_form);

  var total_question = form.total_question;
  total_question = parseInt(total_question);

  var answer_result = '';
  for(var i=1; i<=total_question; i++){
    var index = 'answer_'+i;
    var answer = form[index];

    if(answer != ''){
      answer_result += '<button type="button" id="btn_'+i+'" class="btn btn-success btn-sm mr-2 mt-1 px-2" onclick="return page('+i+')">'+i+'</button>';
    }else{
      answer_result += '<button type="button" id="btn_'+i+'" class="btn btn-light btn-sm mr-2 mt-1 px-2" onclick="return page('+i+')">'+i+'</button>';
    }
  }
  $('#show_answer').html(answer_result);

  var form = $('#exam');
  $.ajax({
    url: base_url+"exam/save_one_essay",
    type: "POST",
    data: form.serialize(),
    dataType: 'JSON',
    success: function(data){
      $('#total_answer').text(data.total_answer);
      $('#total_not_answer').text(data.total_not_answer);
    }
  });
}

function remaining_time(time){
  var get_time = new Date(time);
  var n = new Date();
  var x = setInterval(function() {
    var now = new Date().getTime();
    var dis = get_time.getTime() - now;
    var h = Math.floor((dis % (1000 * 60 * 60 * 60)) / (1000 * 60 * 60));
    var m = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60));
    var s = Math.floor((dis % (1000 * 60)) / (1000));
    h = ("0" + h).slice(-2);
    m = ("0" + m).slice(-2);
    s = ("0" + s).slice(-2);
    var cd = h + ":" + m + ":" + s;
    $('#remaining_time').html(cd);
  }, 100);
  setTimeout(function(){
    clearInterval(x);
    modal_time_end();
    $('#remaining_time').text('00:00:00');
  }, (get_time.getTime() - n.getTime()));
}

function getFormData($form) {
  var unindexed_array = $form.serializeArray();
  var indexed_array = {};
  $.map(unindexed_array, function (n, i) {
    indexed_array[n['name']] = n['value'];
  });
  return indexed_array;
}

function finish()
{
  $.ajax({
    url : base_url+"exam/save_end_essay",
    type: "POST",
    dataType: "JSON",
    data: {id_test:id_test},
    cache: false,
    success: function(data){
      if(data.status){
        location.href = base_url+"exam/essay";
      }
    }
  });
}

function save_end()
{
  save();

  swal({
    title: 'Apakah Anda Yakin?',
    text: 'Pastikan semua soal sudah dijawab dengan benar.',
    icon: 'warning',
    buttons: true,
    dangerMode:true
  })
  .then((willOK) => {
    if (willOK) {
      finish();
    }
  });
}

function modal_time_end()
{
  $('#modal_finish').modal('show');
}

function time_end()
{
  finish();
}