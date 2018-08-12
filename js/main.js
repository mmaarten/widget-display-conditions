(function()
{
	function WDC( elem, options )
	{
		this.$elem   = jQuery( elem );
		this.options = jQuery.extend( {}, WDC.defaultOptions, options );

		var _this = this;

		// Add condition group button click
		this.$elem.on( 'click', '.add-condition-group', function( event )
		{
			event.preventDefault();
			
			// Adds condition group

			_this.addConditionGroup();
		});

		this.$elem.on( 'wdc/conditionGroupAdded', function( event, $group )
		{
			_this.$elem.addClass( 'has-conditions' );
		});

		this.$elem.on( 'wdc/conditionGroupRemoved', function( event, $group, index )
		{
			// Removes group when no conditions

			if ( ! _this.$elem.find( '.condition-group' ).length ) 
			{
				_this.$elem.removeClass( 'has-conditions' );
			}
		});

		this.$elem.on( 'wdc/conditionAdded', function( event, $condition )
		{
			$group = $condition.closest( '.condition-group' );
		});

		this.$elem.on( 'wdc/conditionRemoved', function( event, $condition, index, $group )
		{
			// Removes group when no conditions

	  		if ( ! $group.find( '.condition' ).length ) 
	  		{
	  			_this.removeConditionGroup( $group.data( 'id' ) );
	  		}
		});

		// Add condition button click
		this.$elem.on( 'click', '.condition-group .condition .and button', function( event )
		{
			event.preventDefault();

			// Adds condition

			var $condition = jQuery( this ).closest( '.condition' );
			var $group     = $condition.closest( '.condition-group' );

			_this.addCondition( $group.data( 'id' ), null, $condition.index() + 1 );
		});

		// Remove condition button click
		this.$elem.on( 'click', '.condition .remove button', function( event )
		{
			event.preventDefault();

			// Removes condition

			var $condition = jQuery( this ).closest( '.condition' );

			_this.removeCondition( $condition.data( 'id' ) );
		});

		// Condition param change
		this.$elem.on( 'change', '.condition .param select', function( event )
		{
			event.preventDefault();

			// Loads param related data

			var $condition = jQuery( this ).closest( '.condition' );

			_this.setParamItems( $condition );
		});

		// Form Submit
		this.$elem.on( 'submit', 'form', function( event )
		{
			event.preventDefault();

			// Gathers condition data

			var data = jQuery( this ).serializeObject();

			var conditions = {};

			if ( data.hasOwnProperty( 'conditions' ) ) 
			{
				conditions = jQuery.extend( {}, data.conditions );
			};

			// Notifies clients

			_this.$elem.trigger( 'wdc/submit', [ conditions ] );
		});

		/**
		 * Preloads Param Items
		 * -----------------------------------------------------------
		 */

		// Checks if conditions

		if ( typeof this.options.conditions === 'object' && Object.keys( this.options.conditions ).length ) 
		{
			this.$elem.addClass( 'loading' );

			var $main   = _this.$elem.find( '.wdc-main' );
			var $loader = _this.$elem.find( '.wdc-loader' );

			$main.hide();
			$loader.show();

			// Gets params

			var params = {};

			jQuery.each( this.options.conditions, function( groupId, groupConditions )
			{
				jQuery.each( groupConditions, function( conditionId, condition )
				{
					params[ condition.param ] = condition.param;
				});
			});

			params = Object.keys( params );

			// Loads params items

			WDC.loadParamItems( params, function()
			{
				_this.$elem.removeClass( 'loading' );

				// Adds conditions to view

				jQuery.each( _this.options.conditions, function( groupId, groupConditions )
				{
					_this.addConditionGroup( groupId, groupConditions );
				});

				// Animation

				$main.show();

				height = $main.height();

				$main.hide();

				$loader
					.css( 'opacity', 0 )
					.animate(
					{
						height : height
					}, 300, function()
					{
						$loader
							.hide()
							.css( 'height', '' )
							.css( 'opacity', '' );

						$main
							.css( 'height', '' )
							.show();
					});
			});
		};

		/* -------------------------------------------------------- */

		jQuery( document ).trigger( 'wdc/init', [ this ] );
	}

	WDC.defaultOptions = 
	{
		ajaxurl    : window.ajaxurl || '',
		conditions : {},
		debug      : true,
		applySearchOptionLength : 50 
	};

	/**
	 * Generate ID
	 *
	 * Generates a unique id.
	 *
	 * @return string
	 */
	WDC.generateId = function()
	{
  		function s4() 
  		{
    		return Math.floor( ( 1 + Math.random() ) * 0x10000 ).toString( 16 ).substring( 1 );
  		}
  	
  		return s4() + s4() + s4();
	};

	/**
	 * Populate Dropdown
	 *
	 * @param jQuery|DOMObject elem The <select> or <optgroup> element.
	 * @param array|object items
	 *
	 * Creates 2 options
	 * [
	 *   { id : 'option value', text : 'option text' },
	 *   { id : 'option value', text : 'option text' }
	 * ]
	 *
	 * Creates 2 option groups (1 option each)
	 * [
	 *   {
	 *     text : 'group label',
	 *     children : [ { id : 'option value', text : 'option text' } ]
	 *   },
	 *   {
	 *     text : 'group label',
	 *     children : [ { id : 'option value', text : 'option text' } ]
	 *   }
	 * ]
	 */
	WDC.populateDropdown = function( elem, items, selected )
	{
		var defaults =
		{
			id       : '',
			text     : ''
		};

		var _this = this;

		jQuery.each( items, function()
		{
			var item = jQuery.extend( {}, defaults, this );

			if ( item.hasOwnProperty( 'children' ) ) 
			{
				var $group = jQuery( '<optgroup></optgroup>' );

				$group
					.attr( 'label', item.text );

				WDC.populateDropdown( $group, item.children, selected );

				jQuery( elem ).append( $group );
			}

			else
			{
				var $option = jQuery( '<option></option>' );

				$option
					.attr( 'value', item.id )
					.text( item.text )
					.prop( 'selected', item.id == selected );

				jQuery( elem ).append( $option );
			}
		});
	};

	WDC.paramItems = {};

	/**
	 * @param string Param name.
	 */
	WDC.getParamItems = function( param, complete )
	{
		if ( typeof complete !== 'function' ) 
		{
			complete = function(){};
		};

		if ( WDC.paramItems.hasOwnProperty( param ) ) 
		{
			complete( WDC.paramItems[ param ] );

			return;
		};

		WDC.loadParamItems( param, function( items )
		{
			complete( items[ param ] );
		});
	};

	/**
	 * @param string|array One param or multiple params.
	 */
	WDC.loadParamItems = function( param, complete )
	{
		if ( typeof complete !== 'function' ) 
		{
			complete = function(){};
		};

		jQuery.ajax(
		{
			url : ajaxurl,
			data : { action : 'wdc_get_param_items', param : param },
			method : 'POST',
			context : this,
			success : function( response )
			{
				var items = response.data;

				jQuery.extend( WDC.paramItems, items );

				complete( items );
			}
		})
	};

	WDC.prototype.getConditionGroup = function( id )
	{
  		return this.$elem.find( '.condition-group' ).filter( function()
  		{
  			return jQuery( this ).data( 'id' ) == id;
  		});
	};

	WDC.prototype.addConditionGroup = function( groupId, conditions )
	{
  		if ( typeof groupId === 'undefined' || ! groupId ) 
  		{
  			groupId = 'group_' + WDC.generateId();
  		};

  		var template = wp.template( 'wdc-condition-group' );

  		var $group = jQuery( template(
  		{
  			id : groupId
  		}));

  		this.$elem.find( '.condition-groups' )
  			.append( $group );

  		this.$elem.trigger( 'wdc/conditionGroupAdded', [ $group ] );

  		// Adds conditions (1 is required)

  		if ( typeof conditions === 'object' && Object.keys( conditions ).length ) 
  		{
  			var _this = this;

  			jQuery.each( conditions, function()
  			{
  				_this.addCondition( groupId, this );
  			});
  		}

  		else
  		{
  			this.addCondition( groupId );
  		}

  		
	};

	WDC.prototype.removeConditionGroup = function( groupId )
	{
  		var $group = this.getConditionGroup( groupId );

  		var index = $group.index();

  		$group.remove();

  		this.$elem.trigger( 'wdc/conditionGroupRemoved', [ $group, index ] );
	};

	WDC.prototype.getCondition = function( id )
	{
  		return this.$elem.find( '.condition' ).filter( function()
  		{
  			return jQuery( this ).data( 'id' ) == id;
  		});
	};

	WDC.prototype.addCondition = function( groupId, data, index )
	{
  		var defaults = 
  		{
  			id       : 'condition_' + WDC.generateId(),
  			param    : '',
  			operator : '',
  			value    : ''
  		};

  		var condition = jQuery.extend( {}, defaults, data );

  		var template = wp.template( 'wdc-condition' );

  		var $condition = jQuery( template(
  		{
  			id    : condition.id,
  			group : groupId
  		}));

		$param    = $condition.find( '.param select' );
		$operator = $condition.find( '.operator select' );
		$value    = $condition.find( '.value select' );

  		// sets param

  		$param.find( 'option' ).filter( function()
  		{
  			return jQuery( this ).attr( 'value' ) == condition.param;
  		}).prop( 'selected', true );

  		// Loads param related data

  		var _this = this;

  		$condition.addClass( 'loading' );

  		this.setParamItems( $condition, condition );

  		// Adds to group

  		var $group = this.getConditionGroup( groupId );

  		if ( typeof index === 'undefined' ) 
  		{
  			index = $group.find( '.condition' ).length;
  		};

  		if ( index <= 0 ) 
  		{
  			$group.find( '.conditions' ).prepend( $condition );
  		}

  		else if ( index >= $group.find( '.condition' ).length )
  		{
  			$group.find( '.conditions' ).append( $condition );
  		}

  		else
  		{
  			$condition.insertBefore( $group.find( '.condition' ).eq( index ) );
  		};

  		this.$elem.trigger( 'wdc/conditionAdded', [ $condition ] );
	};

	WDC.prototype.removeCondition = function( conditionId )
	{
  		var $condition = this.$elem.find( '.condition' ).filter( function()
  		{
  			return jQuery( this ).data( 'id' ) == conditionId;
  		});

  		var $group = $condition.closest( '.condition-group' );

  		var index = $condition.index();

  		$condition.remove();

  		this.$elem.trigger( 'wdc/conditionRemoved', [ $condition, index, $group ] );
	};

	WDC.prototype.setParamItems = function( $condition, data )
	{
		data = jQuery.extend( 
		{
			operator : '',
			value    : ''
		}, data );

		// Loads param related data

		$param    = $condition.find( '.param select' );
		$operator = $condition.find( '.operator select' );
		$value    = $condition.find( '.value select' );

		$condition.addClass( 'loading' );

		var _this = this;

  		WDC.getParamItems( $param.val(), function( items )
  		{
  			WDC.populateDropdown( $operator.empty(), items.operators, data.operator );
  			WDC.populateDropdown( $value.empty(), items.values, data.value );

  			if ( $value.next( '.select2' ).length ) 
			{
				$value.select2( 'destroy' );
			}

			if ( $value.find( 'option' ).length >= _this.options.applySearchOptionLength )
			{
				$value.select2();
			}

			$condition.removeClass( 'loading' );
  		});
	};

	/**
	 * jQuery Plugin
	 */
	jQuery.fn.widgetDisplayConditions = function( options )
	{
		return this.each( function()
		{
			if ( jQuery( this ).data( 'widgetDisplayConditions' ) ) 
			{
				return true;
			};

			var instance = new WDC( this, options );

			jQuery( this ).data( 'widgetDisplayConditions', instance );
		});
	};

	/**
	 * Assigns our class to the global scope
	 */
	window.widgetDisplayConditions = WDC;

})();
(function()
{
	/**
	 * Widget form
	 */
	jQuery( window ).load( function()
	{
		var template = wp.template( 'wdc-settings' );

		// Settings button click

		jQuery( document ).on( 'click', '.widget .wdc-settings-button', function()
		{
			var $widget = jQuery( this ).closest( '.widget' );

			var $conditions = $widget.find( '.wdc-conditions' );

			var $settings = jQuery( template() );

			// Opens settings page

			jQuery.featherlight( $settings, 
			{
				namespace : 'wdc-modal',
				afterContent : function()
				{
					var modal = this;

					this.$content.eq(0) // TODO : has a length of 2?
						.widgetDisplayConditions(
						{
							conditions : JSON.parse( $conditions.val() )
						})
						.on( 'wdc/submit', function( event, conditions )
						{
							// Stores conditions in widget field.
							// Trigger change so user can click the save button.

							$conditions
								.val( JSON.stringify( conditions ) )
								.trigger( 'change' );

							modal.close();
						});
				}
			});
		});
	});

})();