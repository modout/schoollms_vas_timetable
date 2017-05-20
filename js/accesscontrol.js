var statuses = [
		    {statusid:'active',name:'Active'},
		    {productid:'inactive',name:'Inactive'}
		];
		
function accessControl()
{
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}	
	
	var searchStr = $("#searchStr").val();
	//alert("Access Control");
	var daurl = "api/process.php?action=USERACCESS&school_id="+school_id+"&search="+searchStr;
	//alert(daurl);
	$('#accesscontrol').datagrid({
		title:'Access Control',
		iconCls:'icon-edit',
		/*toolbar:'#teacher_toolbar',*/
		width:1000,
		height:250,
		singleSelect:false,
		idField:'user_id',
		url:daurl,
		columns:[[
			{field:'user_id',title:'User ID',width:60,hidden:true},
			{field:'ck',checkbox:true},
			{field:'type_title',width:150,title:'User Type'},
			{field:'access_id',width:150,title:'ID Number'},
			{field:'name',width:150,title:'Name'},
			{field:'surname',width:150,align:'right',title:'Surname'},
			{field:'status',width:150,align:'right',title:'Current Status'},
			
            {field:'action',title:'Action',width:80,align:'center',
                formatter:function(value,row,index){
					
					if(row.status == "active")
					{
						 var s = '<a href="javascript:void(0)" onclick="toggle(this)">De-Activate</a> ';
						 return s;
					}
					else{
						var s = '<a href="javascript:void(0)" onclick="toggle(this)">Activate</a> ';
						return s;
					}
                    if (row.editing){
                        var s = '<a href="javascript:void(0)" onclick="saverow(this)">Save</a> ';
                        var c = '<a href="javascript:void(0)" onclick="cancelrow(this)">Cancel</a>';
                        return s+c;
                    } else {
                        var e = '<a href="javascript:void(0)" onclick="editrow(this)">Edit</a> ';
                        var d = '<a href="javascript:void(0)" onclick="deleterow(this)">Delete</a>';
                        return e+d;
                    }
                }
            }
		]],
		onBeforeEdit:function(index,row){
			row.editing = true;
			updateActions(index);
		},
		onAfterEdit:function(index,row){
			row.editing = false;
			updateActions(index);
		},
		onCancelEdit:function(index,row){
			row.editing = false;
			updateActions(index);
		}
	});
}

function updateActions(index){
	$('#accesscontrol').datagrid('refreshRow', index);
}
function getRowIndex(target){
	var tr = $(target).closest('tr.datagrid-row');
	return parseInt(tr.attr('datagrid-row-index'));
}
function editrow(target){
	$('#accesscontrol').datagrid('beginEdit', getRowIndex(target));
}
function rowSelected(target)
{
	var index= getRowIndex(target);
	var rows = $('#accesscontrol').datagrid('getRows');
	var row = rows[index];
	if (row.editing){
		$('#accesscontrol').datagrid('endEdit', getRowIndex(target));
	}
	else{
		$('#accesscontrol').datagrid('beginEdit', getRowIndex(target));
	}
}
function deleterow(target){
	$.messager.confirm('Confirm','Are you sure?',function(r){
		if (r){
			$('#accesscontrol').datagrid('deleteRow', getRowIndex(target));
		}
	});
}

function toggle(target)
{
	var index= getRowIndex(target);
	var rows = $('#accesscontrol').datagrid('getRows');
	var row = rows[index];
	alert(row.user_id);
}

function saverow(target){
	$('#accesscontrol').datagrid('endEdit', getRowIndex(target));
}
function cancelrow(target){
	$('#accesscontrol').datagrid('cancelEdit', getRowIndex(target));
}
function insert(){
	var row = $('#accesscontrol').datagrid('getSelected');
	if (row){
		var index = $('#accesscontrol').datagrid('getRowIndex', row);
	} else {
		index = 0;
	}
	$('#accesscontrol').datagrid('insertRow', {
		index: index,
		row:{
			status:'P'
		}
	});
	$('#accesscontrol').datagrid('selectRow',index);
	$('#accesscontrol').datagrid('beginEdit',index);
}

function toggleactive(access_id)
{
	alert(access_id);
}