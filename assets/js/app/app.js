function reload_table()
{
  table.ajax.reload(null,false); //reload datatable ajax 
}

function message(type,title,message)
{
  swal(message, {
    icon: type,
    title: title
  });
}