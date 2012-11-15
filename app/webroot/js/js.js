$(document).ready(function(){
	initPermissionListing();
	bindCheckBoxClick();
})

function initPermissionListing(){
	$('#rolelist input[type=checkbox]:checked').each(function(){
		var rolegroup = $(this).attr('ref');
		var group = $(this).attr('class');
		
		if( rolegroup == 'rolegroup-0' ){
			$('.'+group).attr('checked', true);
		}
		
		if( rolegroup == 'rolegroup-1' ){
			var temp_rolegroup= $(this).attr('id').split('-');
			var rolegroup = 'rolegroup-' + temp_rolegroup[1];
			$('.'+group).each(function(){
	    		if($(this).attr('ref') == rolegroup){
	    			$(this).attr('checked', true);
	    		}
	    	})
		}
	})
}

function bindCheckBoxClick(){
	$('#rolelist input[type=checkbox]').click(function(){
		var leaf = true;
		
		var checked_status = $(this).attr('checked');
		if(typeof(checked_status) == 'undefined') 
			checked_status = false;
		
		
		var rolename = $(this).attr('id');
	    var rolegroup = $(this).attr('ref');
	    var group = $(this).attr('class');
	    
	    
	    // set full permission to group
	    if( rolegroup == 'rolegroup-0' ){
	    	$('.'+group).attr('checked', checked_status);
	    	leaf = false;
	    }
	    
	    var group_temp = group.split('-');
		var group_id = group_temp[1];
		
	    // set permission to module
	    if( rolegroup == 'rolegroup-1' ){
	    	var temp = rolename.split('-');
	    	var sub_role = temp[1]; // child's role group.
	    	var sub_rolegroup = 'rolegroup-'+sub_role;
	 
	    	$('.'+group).each(function(){
	    		if($(this).attr('ref') == sub_rolegroup){
	    			$(this).attr('checked', checked_status);
	    		}
	    	})
	    	leaf = false;
	    }
	    
	    //controll full access checkbox
	    if(checked_status){
	    	if(leaf){
		    	var parent_id = 'role-';
	    		var temp = rolegroup.split('-');
	    		parent_id += temp[1] + '-' + group_id;
	    		
	    		if($('#'+parent_id).length > 0){
	    			var current_rolegroup = 'rolegroup-' + temp[1];
	    			
	    			var grouptotal = 0;
	    			var groupchecked = 0;
	    			
	    			$('.'+group).each(function(){
	    	    		if($(this).attr('ref') == current_rolegroup){
	    	    			grouptotal++; // increase total count
	    	    			if($(this).attr('checked')) groupchecked++; // increase checked count
	    	    		}
	    	    	})
	    	    
	    			if(grouptotal == groupchecked){
			    		$('#'+parent_id).attr('checked', checked_status);
			    		
			    		var ascent_rolegroup = $('#'+parent_id).attr('ref');
			    		var temp = ascent_rolegroup.split('-');
			    		var ascent_id = 'role-'+temp[1]+'-'+ group_id;
			    		
			    		$('#'+ascent_id).attr('checked', checked_status);
	    			}
	    		}
	    	}
    		var total = $('.'+group).length - 1; // uncount the full access;
    		var checked = $('.'+group+':checked').length;
    		
    		if( total ==  checked){
    			var ascent_id = 'role-1-'+group_id;
    			
    			$('#'+ascent_id).attr('checked', checked_status);
    		}
    	}else{
    		var parent_id = 'role-';
    		var temp = rolegroup.split('-');
    		parent_id += temp[1] + '-' + group_id;
    		
    		if($('#'+parent_id).length > 0){
	    		$('#'+parent_id).attr('checked', checked_status);
	    		
	    		var ascent_rolegroup = $('#'+parent_id).attr('ref');
	    		var temp = ascent_rolegroup.split('-');
	    		var ascent_id = 'role-'+temp[1]+'-'+ group_id;
	    		
	    		$('#'+ascent_id).attr('checked', checked_status);
    		}
    	}
	});
}