/**
 * UI
 */
(function( $ )
{
	"use strict";

	window.wdc = window.wdc || {};

	function UI( elem, options )
	{
		this.$elem   = $( elem );
		this.options = $.extend( {}, UI.defaultOptions, options );
	
		this.$submit    = this.$elem.find( ':input[type="submit"]' );	
		this.fieldItems = {};
		this.isLoaded   = false;
		this.isSaved    = false;

		this.setSaved( 'saved' );

		var _this = this;

		this.$elem.on( 'click', '.wdc-add-condition-group', function( event )
		{
			var $group = _this.createConditionGroup();

			var $condition = _this.createCondition( { group : $group.data( 'id' ) } );

			$group.find( '.wdc-conditions' ).append( $condition );

			_this.$elem.find( '.wdc-condition-groups' ).append( $group );
		});

		this.$elem.on( 'click', '.wdc-add-condition', function( event )
		{
			var $condition = $( this ).closest( '.wdc-condition' );
			var $group     = $condition.closest( '.wdc-condition-group' );

			var $newCondition = _this.createCondition( { group : $group.data( 'id' ) } );

			$newCondition.insertAfter( $condition );
		});

		this.$elem.on( 'click', '.wdc-remove-condition', function( event )
		{
			var $condition = $( this ).closest( '.wdc-condition' );
			var $group     = $condition.closest( '.wdc-condition-group' );

			$condition.remove();

			if ( 0 == $group.find( '.wdc-condition' ).length ) 
			{
				$group.remove();
			}
		});

		this.$elem.on( 'change', '.wdc-param', function( event )
		{
			var $condition = $( this ).closest( '.wdc-condition' );

			_this.updateConditionFields( $condition );
		});

		this.$elem.on( 'change', '.wdc-param, .wdc-operator, .wdc-value', function( event )
		{
			_this.setSaved( 'save' );
		});

		this.$elem.on( 'submit', 'form', function( event )
		{
			event.preventDefault();

			_this.setSaved( 'saving' );

			$.post( ajaxurl, $( this ).serialize(), function( response )
			{
				console.log( response );

				_this.setSaved( 'saved' );
			});
		});

		this.$elem.on( 'DOMNodeInserted DOMNodeRemoved', function( event )
		{
			var $elem = $( event.target );

			if ( _this.$elem.find( '.wdc-condition' ).length ) 
			{
				_this.$elem.addClass( 'wdc-has-conditions' );
			}

			else
			{
				_this.$elem.removeClass( 'wdc-has-conditions' );
			}

			if ( _this.isLoaded && ( $elem.is( '.wdc-condition-group' ) || $elem.is( '.wdc-condition' ) ) ) 
			{
				_this.setSaved( 'save' );
			}
		});

		this.preload();
	}

	UI.defaultOptions = 
	{
		widget    : '',
		nonceName : '',
		nonce     : '',
	};

	UI.generateId = function()
	{
		return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function( c ) 
		{
    		var r = Math.random() * 16 | 0, v = c == 'x' ? r : ( r & 0x3 | 0x8 );
    		
    		return v.toString( 16 );
  		});
	};

	UI.populateSelectField = function( $select, items )
	{
		$select.empty();

		$.each( items, function( i, item )
		{
			item = $.extend( 
			{
				id       : '',
				text     : '',
				selected : false,
			}, item );

			if ( typeof item.children !== 'undefined' ) 
			{
				var $group = $( '<optgroup></optgroup>' );

				$group.attr( 'label', item.text );

				UI.populateSelectField( $group, item.children );

				$select.append( $group );
			}

			else
			{
				var $option = $( '<option></option>' );

				$option
					.val( item.id )
					.text( item.text )
					.prop( 'selected', item.selected ? true : false );

				$select.append( $option );
			}

		});
	};

	UI.setSelected = function( $select, selected )
	{
		$select.find( 'option' ).filter( function()
		{
			return selected == $( this ).val();

		}).prop( 'selected', true );
	};

	UI.prototype.trigger = function() 
	{
		arguments[0] = 'wdc.' + arguments[0];

		this.$elem.trigger.apply( this.$elem, arguments );
	};

	UI.prototype.on = function() 
	{
		arguments[0] = 'wdc.' + arguments[0];
		
		this.$elem.on.apply( this.$elem, arguments );
	};

	UI.prototype.createConditionGroup = function( data ) 
	{
		data = $.extend(
		{
			id : UI.generateId(),
		}, data );

		var $elem = $( wp.template( 'wdc-condition-group' )(
		{
			id : data.id,
		}));

		return $elem;
	};

	UI.prototype.createCondition = function( data ) 
	{
		data = $.extend(
		{
			id       : UI.generateId(),
			param    : '',
			operator : '',
			value    : '',
			group    : '',
		}, data );

		var $elem = $( wp.template( 'wdc-condition' )(
		{
			id    : data.id,
			group : data.group,
		}));

		UI.setSelected( $elem.find( '.wdc-param' ), data.param );

		this.updateConditionFields( $elem,
		{
			operator : data.operator,
			value    : data.value,
		});

		return $elem;
	};

	UI.prototype.updateConditionFields = function( $condition, selected ) 
	{
		selected = $.extend( {}, selected );

		var $param    = $condition.find( '.wdc-param' );
		var $operator = $condition.find( '.wdc-operator' );
		var $value    = $condition.find( '.wdc-value' );

		// Disable fields
		$operator.prop( 'disabled', true );
		$value.prop( 'disabled', true );

		// Load field items
		this.getConditionFieldItems( $condition, function( items )
		{
			items = $.extend( {}, items );

			// Populate fields
			UI.populateSelectField( $operator, items.operator );
			UI.populateSelectField( $value, items.value );

			// Set selected
			if ( typeof selected.operator !== 'undefined' ) UI.setSelected( $operator, selected.operator );
			if ( typeof selected.value    !== 'undefined' ) UI.setSelected( $value, selected.value );

			// Enable fields
			$operator.prop( 'disabled', false );
			$value.prop( 'disabled', false );
		});
	};

	UI.prototype.getConditionFieldItems = function( $condition, callback ) 
	{
		var param = $condition.find( '.wdc-param' ).val();

		// Check if already loaded

		if ( typeof this.fieldItems[ param ] !== 'undefined' ) 
		{
			// Callback
			callback( this.fieldItems[ param ] );

			return;
		}

		// Load field items

		var _this = this;

		var data = this.prepareAjax( 
		{
			action : 'wdc_ui_get_condition_field_items',
			param  : param,
		});

		$.post( ajaxurl, data, function( items )
		{
			console.log( items );

			// Save items
			_this.fieldItems[ param ] = $.extend( {}, items );

			// Callback
			callback( _this.fieldItems[ param ] );
		});
	};

	UI.prototype.preload = function() 
	{
		var _this = this;

		this.$elem.addClass( 'wdc-loading' );

		var data = this.prepareAjax( 
		{
			action : 'wdc_ui_preload',
			widget : this.options.widget,
		});

		$.post( ajaxurl, data, function( response )
		{
			console.log( response );

			_this.fieldItems = $.extend( {}, response.fieldItems );

			$.each( response.conditions, function( groupId, conditions )
			{
				var $group = _this.createConditionGroup( { id : groupId } );

				$.each( conditions, function( conditionId, condition )
				{
					var $condition = _this.createCondition( 
					{ 
						id       : conditionId,
						param    : condition.param,
						operator : condition.operator,
						value    : condition.value,
						group    : groupId,
					});

					$group.find( '.wdc-conditions' ).append( $condition );
				});

				_this.$elem.find( '.wdc-condition-groups' ).append( $group );
			});

			_this.$elem.removeClass( 'wdc-loading' );

			_this.isLoaded = true;

			_this.trigger( 'preloadComplete' );
		});
	};

	UI.prototype.setSaved = function( state ) 
	{
		// Backup submit text
		if ( typeof this.$submit.data( 'save' ) === 'undefined' ) 
		{
			this.$submit.data( 'save', this.$submit.text() );
		}

		this.isSaved = false;

		switch ( state )
		{
			case 'save' :

				this.$submit
					.text( this.$submit.data( 'save' ) )
					.prop( 'disabled', false )
						.siblings( '.spinner' )
							.removeClass( 'is-active' );

				break;

			case 'saved' :

				this.$submit
					.text( this.$submit.data( 'saved' ) )
					.prop( 'disabled', true )
						.siblings( '.spinner' )
							.removeClass( 'is-active' );

				this.isSaved = true;

				break;

			case 'saving' :

				this.$submit
					.text( this.$submit.data( 'save' ) )
					.prop( 'disabled', true )
						.siblings( '.spinner' )
							.addClass( 'is-active' );

				break;
		}
	};

	UI.prototype.prepareAjax = function( data ) 
	{
		data = $.extend( {}, data );

		if ( this.options.nonceName ) 
		{
			data[ this.options.nonceName ] = this.options.nonce;
		}

		return data;
	};
	
	window.wdc.ui = UI;

})( jQuery );

/**
 * Open UI
 */
(function( $ )
{
	"use strict";

	$( document.body ).on( 'click', '.wdc-open-ui', function( event )
	{
		var $button  = $( this );
		var $spinner = $button.siblings( '.spinner' );

		$spinner.addClass( 'wdc-is-active' );
		
		// Get content
		var content = wp.template( 'wdc-ui' )(
		{
			widget : $button.data( 'widget' ),
		});

		// Instantiate
		var ui = new wdc.ui( content, 
		{
			widget    : $button.data( 'widget' ),
			nonceName : $button.data( 'noncename' ),
			nonce     : $button.data( 'nonce' ),
		});

		// Preload complete
		ui.on( 'preloadComplete', function( event )
		{
			$spinner.removeClass( 'wdc-is-active' );

			// Open content inside modal
			$.featherlight( ui.$elem, 
			{
				namespace    : 'wdc-modal',
				persist      : true,
				closeOnClick : false,
				closeOnEsc   : true,
				
				afterContent : function()
				{
					
				},

				beforeClose : function()
				{
					// Unsaved data confirmation
					if ( ! ui.isSaved && wdc.messages.notSaved ) 
					{
						return window.confirm( wdc.messages.notSaved );
					}
				}
			});
		});

	});
	
})( jQuery );
