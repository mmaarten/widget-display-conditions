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

		this.$submit   = this.$elem.find( 'input[type="submit"]' );
		this.fieldData = {};
		this.isLoaded  = false;

		this.setSubmit( 'saved' );

		var _this = this;

		this.$elem.on( 'change', ':input', function( event )
		{
			_this.setSubmit( 'unsaved' );
		});

		this.$elem.on( 'DOMNodeInserted DOMNodeRemoved', function( event )
		{
			var $elem = $( event.target );

			if ( _this.isLoaded && ( $elem.is( ':input' ) || $elem.find( ':input' ).length ) ) 
			{
				_this.setSubmit( 'unsaved' );
			};
			
			if ( _this.$elem.find( '.wdc-condition' ).length ) 
			{
				_this.$elem.addClass( 'wdc-has-conditions' );
			}

			else
			{
				_this.$elem.removeClass( 'wdc-has-conditions' )
			}
		});

		this.$elem.on( 'click', '.wdc-add-condition-group', function( event )
		{
			// Add condition group

			var $group = _this.createConditionGroup();

			_this.$elem.find( '.wdc-condition-groups' ).append( $group );

			// Add condition inside group

			var $condition = _this.createCondition( { group : $group.data( 'id' ) } );

			$group.find( '.wdc-conditions' ).append( $condition );
		});

		this.$elem.on( 'click', '.wdc-add-condition', function( event )
		{
			var $condition = $( this ).closest( '.wdc-condition' );
			var $group     = $condition.closest( '.wdc-condition-group' );

			// Create new condition and add it after the condition

			var $newCondition = _this.createCondition( { group : $group.data( 'id' ) } );

			$newCondition.insertAfter( $condition );
		});

		this.$elem.on( 'click', '.wdc-remove-condition', function( event )
		{
			var $condition = $( this ).closest( '.wdc-condition' );
			var $group     = $condition.closest( '.wdc-condition-group' );
			
			// Remove condition
			$condition.remove();

			// Remove condition group when empty
			if ( ! $group.find( '.wdc-condition' ).length ) 
			{
				$group.remove();
			}
		});

		// Param change
		this.$elem.on( 'change', '.wdc-param', function( event )
		{
			var $condition = $( this ).closest( '.wdc-condition' );

			// Populate condition 'operator' and 'value' fields

			var $param    = $condition.find( '.wdc-param' );
			var $operator = $condition.find( '.wdc-operator' );
			var $value    = $condition.find( '.wdc-value' );

			// Disable fields temporary until loading is complete.
			$operator.prop( 'disabled', true );
			$value.prop( 'disabled', true );

			// Load field items
			_this.getConditionFieldsItems( $condition, function( items )
			{
				// Populate fields
				UI.populateSelectField( $operator, items.operator );
				UI.populateSelectField( $value, items.value );

				// Enable fields
				$operator.prop( 'disabled', false );
				$value.prop( 'disabled', false );
			});
		});

		// Form submit
		this.$elem.on( 'submit', 'form', function( event )
		{
			event.preventDefault();

			var $spinner = _this.$submit.siblings( '.spinner' );

			$spinner.addClass( 'is-active' );

			// Save condition data
			$.post( _this.options.ajaxurl, $( this ).serialize(), function( response )
			{
				console.log( response );

				$spinner.removeClass( 'is-active' );

				_this.$submit.val( _this.$submit.data( 'saved' ) );
				_this.$submit.prop( 'disabled', true );
			});
		});

		// Load

		console.log( 'loading' );

		this.$elem.addClass( 'wdc-loading' );

		this.preload( function( data )
		{
			console.log( 'loaded', data );

			_this.fieldData = $.extend( {}, data.fieldData );

			$.each( data.conditions, function( groupId, conditions )
			{
				// Add group

				var $group = _this.createConditionGroup( { id : groupId } );

				_this.$elem.find( '.wdc-condition-groups' ).append( $group );

				// Add conditions

				$.each( conditions, function( conditionId, condition )
				{
					var $condition = _this.createCondition( 
					{ 
						id       : conditionId,
						param    : condition.param,
						operator : condition.operator,
						value    : condition.value,
						group    : $group.data( 'id' ) 
					});

					$group.find( '.wdc-conditions' ).append( $condition );
				});
			});

			_this.$elem.removeClass( 'wdc-loading' );

			_this.isLoaded = true;

			$( _this ).trigger( 'loaded' );
		});
	}

	UI.defaultOptions = 
	{
		widgetId  : '',
		nonceName : '',
		nonce     : '',
		ajaxurl   : window.ajaxurl || '',
	};

	UI.generateId = function()
	{
		return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace( /[xy]/g, function( c ) 
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
			item = $.extend( { id : '', text : '', selected : false }, item );

			// Create <optgroup>

			if ( typeof item.children !== 'undefined' ) 
			{
				var $optgroup = $( '<optgroup></optgroup>' );

				$optgroup.attr( 'label', item.text );

				UI.populateSelectField( $optgroup, item.children );

				$select.append( $optgroup );
			}

			// Create <option>

			else
			{
				var $option = $( '<option></option>' );

				$option
					.text( item.text )
					.val( item.id )
					.prop( 'selected', item.selected ? true : false )

				if ( item.html !== undefined ) 
				{
					$option.html( item.html );
				}

				$select.append( $option );
			}
		});
	};

	UI.prototype.setSubmit = function( state )
	{
		if ( typeof this.$submit.data( 'unsaved' ) === 'undefined' ) 
		{
			this.$submit.data( 'unsaved', this.$submit.val() );
		}

		switch( state )
		{
			case 'saved' :
				this.$submit.val( this.$submit.data( 'saved' ) );
				this.$submit.prop( 'disabled', true );
				break;

			case 'unsaved' :
				this.$submit.val( this.$submit.data( 'unsaved' ) );
				this.$submit.prop( 'disabled', false );
				break;
		}
	};

	UI.prototype.createConditionGroup = function( data ) 
	{
		var defaults = 
		{
			id : UI.generateId(),
		};

		var data = $.extend( {}, defaults, data );

		var $group = $( wp.template( 'wdc-condition-group' )(
		{
			id : data.id,
		}));

		return $group;
	};

	UI.prototype.createCondition = function( data ) 
	{
		var defaults = 
		{
			id       : UI.generateId(),
			param    : '',
			operator : '',
			value    : '',
			group    : '', // required
		};

		var data = $.extend( {}, defaults, data );

		if ( ! data.group ) 
		{
			console.warn( 'condition group should be set.' );
		};

		var $condition = $( wp.template( 'wdc-condition' )(
		{
			id    : data.id,
			group : data.group,
		}));

		var $param    = $condition.find( '.wdc-param' );
		var $operator = $condition.find( '.wdc-operator' );
		var $value    = $condition.find( '.wdc-value' );

		// Set selected param
		$param.find( 'option' ).filter( function()
		{
			return data.param == $( this ).val();
			
		}).prop( 'selected', true );

		// Populate condition 'operator' and 'value' fields

		// Disable fields temporary until loading is complete.
		$operator.prop( 'disabled', true );
		$value.prop( 'disabled', true );

		// Load field items
		this.getConditionFieldsItems( $condition, function( items )
		{
			// Populate fields
			UI.populateSelectField( $operator, items.operator );
			UI.populateSelectField( $value, items.value );

			// Set selected operator
			$operator.find( 'option' ).filter( function()
			{
				return data.operator == $( this ).val();

			}).prop( 'selected', true );

			// Set selected value
			$value.find( 'option' ).filter( function()
			{
				return data.value == $( this ).val();
				
			}).prop( 'selected', true );

			// Enable fields
			$operator.prop( 'disabled', false );
			$value.prop( 'disabled', false );
		});

		// Return

		return $condition;
	};

	UI.prototype.preload = function( callback ) 
	{
		var data = this.prepareAjax( 
		{ 
			action : 'wdc_ui_preload', 
			widget : this.options.widgetId,
		});

		return $.post( this.options.ajaxurl, data, callback );
	};

	UI.prototype.getConditionFieldsItems = function( $condition, callback ) 
	{
		var _this = this;

		var param = $condition.find( '.wdc-param' ).val();

		// Check if already loaded

		if ( typeof this.fieldData[ param ] !== 'undefined' ) 
		{
			// callback
			callback( this.fieldData[ param ] );

			return;
		}

		// load

		var data = this.prepareAjax( 
		{ 
			action : 'wdc_ui_get_condition_fields_items', 
			param  : param,
		});

		return $.post( this.options.ajaxurl, data, function( items )
		{
			// save data
			_this.fieldData[ param ] = $.extend( {}, items );

			// callback
			callback( _this.fieldData[ param ] );
		});
	};

	UI.prototype.prepareAjax = function( data ) 
	{
		data = $.extend( {}, data );

		// Add nonce
		if ( this.options.nonceName ) 
		{
			data[ this.options.nonceName ] = this.options.nonce;
		}

		return data;
	};

	window.wdc.UI = UI;

})( jQuery );
/**
 * Open UI inside modal
 */
(function( $ )
{
	"use strict";

	$( document.body ).on( 'click', '.wdc-open-ui', function( event )
	{
		var $button  = $( this );
		var $spinner = $button.siblings( '.spinner' );

		var content = wp.template( 'wdc-ui' )(
		{
			widget : $button.data( 'widget' ),
		});

		var ui = new wdc.UI( content, 
		{
			widgetId  : $button.data( 'widget' ),
			nonceName : $button.data( 'noncename' ),
			nonce     : $button.data( 'nonce' ),
		});

		$spinner.addClass( 'wdc-is-active' );

		$( ui ).on( 'loaded', function( event )
		{
			$spinner.removeClass( 'wdc-is-active' );

			$.featherlight( ui.$elem, 
			{
				namespace : 'wdc-modal',
				persist : true,

				afterContent : function()
				{
					ui.setSubmit( 'saved' );
				}
			});
		});
	});

})( jQuery );
