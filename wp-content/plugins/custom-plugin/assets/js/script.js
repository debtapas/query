/* This is our js file */
// jQuery(function(){
// 	jQuery(document).on("click",".btnClick", function(){
// 		console.log(ajaxurl);

// 		var post_data = "action=custom_plugin_library&param=get_message";
// 		$.post(ajaxurl,post_data,function(response){ //url, parameter, function name
// 			console.log(response);
// 		}); 
// 	});
// });


// jQuery(document).ready( function(){    
// 	jQuery('.btnClick').on('click', function(e){
// 		e.preventDefault();
//         // var rml_post_id = jQuery(this).data( 'id' );    
//         jQuery.ajax({
//             url : ajaxurl,
//             type : 'post',
//             data : {
//                 action : 'demo_ajax',
//                 // post_id : rml_post_id
//             },
//             success : function( response ) {
//                 // jQuery('.rml_contents').html(response);
//                 console.log(response);
//             }
//         });
// 	}); 
    // jQuery('#content').on('click', '.btnClick', function(e) { 
    //     e.preventDefault();
    //     // var rml_post_id = jQuery(this).data( 'id' );    
    //     jQuery.ajax({
    //         url : ajaxurl,
    //         type : 'post',
    //         data : {
    //             action : 'demo_ajax',
    //             // post_id : rml_post_id
    //         },
    //         success : function( response ) {
    //             // jQuery('.rml_contents').html(response);
    //             console.log(response);
    //         }
    //     });
    //     // jQuery(this).hide();            
    // });     
// });


jQuery(document).ready( function(){    
    jQuery('.btnClick').on('click', function(e){
        // alert('fsdgdfg');
        e.preventDefault();
        // var rml_post_id = jQuery(this).data( 'id' );    
        jQuery.ajax({
            url : ajaxurl,
            type : 'post',
            data : {
                action : 'demo_ajax',
                // post_id : rml_post_id
            },
            success : function( response ) {
                // jQuery('.rml_contents').html(response);
                console.log(response);
            }
        });
    }); 
});