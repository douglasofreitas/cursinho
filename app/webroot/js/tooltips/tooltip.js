	//Tooltips functions
	
	function tooltip(){
		
	    $('.tipBottom').poshytip({
				className: 'tip-twitter',
				showOn: 'focus',
	            alignTo: 'target',
	            alignX: 'center',
	            alignY: 'bottom',
	            offsetX: 0,
	            offsetY: 5			
		});
		
		$('.tipTop').poshytip({
	           className: 'tip-twitter',
	           showOn: 'focus',
	           alignTo: 'target',
	           alignX: 'inner-left',
	           offsetX: 0,
	           offsetY: 5
         });
		 
		 $('.tipLeft').poshytip({
	          className: 'tip-twitter',
	          showOn: 'focus',
	          alignTo: 'target',
	          alignX: 'left',
	          alignY: 'center',
	          offsetX: 5
        });
		
		$('.tipRight').poshytip({
	         className: 'tip-twitter',
	         showOn: 'focus',
	         alignTo: 'target',
	         alignX: 'right',
	         alignY: 'center',
	         offsetX: 5
        });
		
		$('.tipHoverTop').poshytip({
	         className: 'tip-twitter',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'center',
	         offsetY: 5,
	         allowTipHover: false,
	         fade: false,
	         slide: false
        });
		
		$('.tipHoverBottom').poshytip({
	         className: 'tip-twitter',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'center',
			 alignY: 'bottom',
	         offsetY: 5,
			 offsetX: 0,
	         allowTipHover: false,
	         fade: false,
	         slide: false
        });
		
		$('.tipHoverLeft').poshytip({
	         className: 'tip-twitter',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'left',
			 alignY: 'center',
	         offsetX: 10,
	         allowTipHover: false,
	         fade: false,
	         slide: false
        });
		
		$('.tipHoverRight').poshytip({
	         className: 'tip-twitter',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'right',
	         alignY: 'center',
	         offsetX: 10,
	         allowTipHover: false,
	         fade: false,
	         slide: false			 
        });
		
		//Alerts
		
		 $('.tipBottomAlert').poshytip({
				className: 'tip-alert',
				showOn: 'focus',
	            alignTo: 'target',
	            alignX: 'center',
	            alignY: 'bottom',
	            offsetX: 0,
	            offsetY: 5			
		});
		
		$('.tipTopAlert').poshytip({
	           className: 'tip-alert',
	           showOn: 'focus',
	           alignTo: 'target',
	           alignX: 'inner-left',
	           offsetX: 0,
	           offsetY: 5
         });
		 
		 $('.tipLeftAlert').poshytip({
	          className: 'tip-alert',
	          showOn: 'focus',
	          alignTo: 'target',
	          alignX: 'left',
	          alignY: 'center',
	          offsetX: 5
        });
		
		$('.tipRightAlert').poshytip({
	         className: 'tip-alert',
	         showOn: 'focus',
	         alignTo: 'target',
	         alignX: 'right',
	         alignY: 'center',
	         offsetX: 5
        });
		
		$('.tipHoverTopAlert').poshytip({
	         className: 'tip-alert',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'center',
	         offsetY: 5,
	         allowTipHover: false,
	         fade: false,
	         slide: false
        });
		
		$('.tipHoverBottomAlert').poshytip({
	         className: 'tip-alert',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'center',
			 alignY: 'bottom',
	         offsetY: 5,
			 offsetX: 0,
	         allowTipHover: false,
	         fade: false,
	         slide: false
        });
		
		$('.tipHoverLeftAlert').poshytip({
	         className: 'tip-alert',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'left',
			 alignY: 'center',
	         offsetX: 10,
	         allowTipHover: false,
	         fade: false,
	         slide: false
        });
		
		$('.tipHoverRightAlert').poshytip({
	         className: 'tip-alert',
	         showTimeout: 1,
	         alignTo: 'target',
	         alignX: 'right',
	         alignY: 'center',
	         offsetX: 10,
	         allowTipHover: false,
	         fade: false,
	         slide: false			 
        });
		
	}
   // End tooltip function