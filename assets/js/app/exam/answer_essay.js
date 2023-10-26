page(1);
// save();

$('#btn-back').hide();
$('#btn-finish').hide();
$("#widget_1").show();

function page(number)
{
  $('#btn-next').attr('rel', (number + 1));
  $('#btn-back').attr('rel', (number - 1));

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
  var id_form = $('#exam_answer');
  var form = getFormData(id_form);

  var total_question = form.total_question;
  total_question = parseInt(total_question);

  var form = $('#exam_answer');
  $.ajax({
    url: base_url+"exam/save_one_essay_answer",
    type: "POST",
    data: form.serialize(),
    dataType: 'JSON',
    success: function(data){
    }
  });
}

function save_end()
{
  save();

  swal({
    title: 'Apakah Anda Yakin?',
    text: 'Pastikan semua soal sudah diberikan nilai.',
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

function finish()
{
  var id_exam_result_essay = $("input[name=id_exam_result_essay]").val();
  $.ajax({
    url : base_url+"exam/save_end_essay_answer",
    type: "POST",
    dataType: "JSON",
    data: {id_exam_result_essay:id_exam_result_essay},
    cache: false,
    success: function(data){
      if(data.status){
        location.href = base_url+"exam/essay";
      }
    }
  });
}

function getFormData($form)
{
  var unindexed_array = $form.serializeArray();
  var indexed_array = {};
  $.map(unindexed_array, function (n, i) {
    indexed_array[n['name']] = n['value'];
  });
  return indexed_array;
}