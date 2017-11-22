(function()
{
	function Plugin( elem, options )
	{
		this.$elem   = jQuery( elem );
		this.options = jQuery.extend( {}, Plugin.defaultOptions, options );

		var _this = this;

		// Add condition group button click
		this.$elem.on( 'click', '.add-condition-group', function( event )
		{
			event.preventDefault();

			_this.addRuleGroup();
		});

		// Add condition button click
		this.$elem.on( 'click', '.condition .and .button', function( event )
		{
			event.preventDefault();

			var $condition = jQuery( this ).closest( '.condition' );
			var $group = $condition.closest( '.condition-group' );

			_this.addRule( $group.data( 'id' ) );
		});

		// Remove condition button click
		this.$elem.on( 'click', '.condition .remove .button', function( event )
		{
			event.preventDefault();

			var $condition = jQuery( this ).closest( '.condition' );

			_this.removeRule( $condition.data( 'id' ) );
		});

		// Rule param change
		this.$elem.on( 'change', '.condition .param select', function( event )
		{
			event.preventDefault();

			var $condition  = jQuery( this ).closest( '.condition' );
			var $param = $condition.find( '.param select' );
			var $value = $condition.find( '.value select' );

			_this.loadRuleValues( $value, $param.val() );
		});

		// Form submit
		this.$elem.on( 'submit', 'form', function( event )
		{
			event.preventDefault();

			// gets all conditions from form

			var data = jQuery( this ).serializeObject(), conditions = {};

			if ( data.hasOwnProperty( 'conditions' ) ) 
			{
				conditions = data.conditions;
			}

			// Notifies clients

			_this.$elem.trigger( 'widgetDisplayRules/submit', [ conditions ] );
		});

		// Rule Removed
		this.$elem.on( 'widgetDisplayRules/conditionRemoved', function( event, $condition, $group )
		{
			// Removes group when no conditions

			if ( ! $group.find( '.condition' ).length ) 
			{
				_this.removeRuleGroup( $group.data( 'id' ) );
			};

			if ( ! _this.$elem.find( '.condition' ).length ) 
			{
				_this.$elem.removeClass( 'has-conditions' );
			}
		});

		// Rule Added
		this.$elem.on( 'widgetDisplayRules/conditionAdded', function( event, $condition )
		{
			_this.$elem.addClass( 'has-conditions' );
		});

		// creates conditions
		jQuery.each( this.options.conditions, function( groupId, conditions )
		{
			_this.addRuleGroup( groupId, conditions );
		});

		//

		jQuery( document ).trigger( 'widgetDisplayRules/init', [ this ] );
	}

	Plugin.defaultOptions = 
	{
		ajaxurl   : window.ajaxurl || '',
		conditions : {}
	};

	Plugin.generateId = function()
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
	 * @param array items
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
	Plugin.populateDropdown = function( elem, items )
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

				Plugin.populateDropdown( $group, item.children );

				jQuery( elem ).append( $group );
			}

			else
			{
				var $option = jQuery( '<option></option>' );

				$option
					.attr( 'value', item.id )
					.text( item.text )

				jQuery( elem ).append( $option );
			}
		});
	};

	Plugin.prototype.loadRuleValues = function( $value, param, selected )
	{
		if ( typeof selected === 'undefined' ) 
		{
			selected = '';
		};

		jQuery.ajax( 
		{
			url : this.options.ajaxurl,
			method : 'POST',
			data : { action : 'wdc_get_condition_param_values', param : param },
			success : function( items )
			{
				$value.empty();

				Plugin.populateDropdown( $value, items );

				$value.find( 'option' ).filter( function()
				{
					return jQuery( this ).attr( 'value' ) == selected;

				}).prop( 'selected', true );
			}
		});
	}

	Plugin.prototype.addRuleGroup = function( id, conditions ) 
	{
		if ( typeof id === 'undefined' ) 
		{
			id = 'group_' + Plugin.generateId();
		};

		conditions = jQuery.extend( {}, conditions );

		/**
		 * View
		 * -----------------------------------------------------------
		 */

		var template = wp.template( 'wdc-condition-group' );
			
		var $group = jQuery( template(
		{
			id : id,
		}));

		this.$elem.find( '.condition-groups' )
			.append( $group );


		this.$elem.trigger( 'widgetDisplayRules/conditionGroupAdded', [ $group ] );

		if ( Object.keys( conditions ).length ) 
		{
			var _this = this;

			jQuery.each( conditions, function()
			{
				_this.addRule( id, this );
			});
		}

		else
		{
			this.addRule( id );
		};
	};

	Plugin.prototype.removeRuleGroup = function( groupId ) 
	{
		// Removes condition

		var $group = this.$elem.find( '.condition-group' ).filter( function()
		{
			return jQuery( this ).data( 'id' ) == groupId;
		});

		if ( ! $group.length ) 
		{
			return;
		};

		$group.remove();

		this.$elem.trigger( 'widgetDisplayRules/conditionGroupRemoved', [ $group ] );
	};

	Plugin.prototype.addRule = function( groupId, data ) 
	{
		/**
		 * Model
		 * -----------------------------------------------------------
		 */

		var defaults = 
		{
			id       : 'condition_' + Plugin.generateId(),
			param    : '',
			operator : '',
			value    : '',
			group    : groupId
		};

		var condition = jQuery.extend( {}, defaults, data );

		/**
		 * View
		 * -----------------------------------------------------------
		 */

		// Gets group

		var $group =this.$elem.find( '.condition-group' ).filter( function()
		{
			return jQuery( this ).data( 'id' ) == condition.group;
		});

		if ( ! $group.length ) 
		{
			return;
		}

		// Creates condition

		var template = wp.template( 'wdc-condition' );
			
		var $condition = jQuery( template(
		{
			id 	  : condition.id,
			group : condition.group
		}));

		$group.find( '.conditions' )
			.append( $condition );

		//

		$param = $condition.find( '.param select' );

		if ( condition.param ) 
		{
			$param.val( condition.param );
		}

		else
		{
			$param.find( 'option:eq(0)' ).prop( 'selected', true );
		};

		// 

		$operator = $condition.find( '.operator select' );

		if ( condition.operator )
		{
			$operator.val( condition.operator );
		}

		else
		{
			$operator.find( 'option:eq(0)' ).prop( 'selected', true );
		};

		// 

		$value = $condition.find( '.value select' );

		//

		this.loadRuleValues( $value, $param.val(), condition.value );

		this.$elem.trigger( 'widgetDisplayRules/conditionAdded', [ $condition ] );
	};

	Plugin.prototype.removeRule = function( conditionId ) 
	{
		// Removes condition

		var $condition = this.$elem.find( '.condition' ).filter( function()
		{
			return jQuery( this ).data( 'id' ) == conditionId;
		});

		if ( ! $condition.length ) 
		{
			return;
		};

		var $group = $condition.closest( '.condition-group' );

		$condition.remove();

		this.$elem.trigger( 'widgetDisplayRules/conditionRemoved', [ $condition, $group ] );
	};

	jQuery.fn.widgetDisplayRules = function( options )
	{
		return this.each( function()
		{
			if ( jQuery( this ).data( 'widgetDisplayRules' ) ) 
			{
				return true;
			};

			var instance = new Plugin( this, options );

			jQuery( this ).data( 'widgetDisplayRules', instance );
		});
	};

	window.widgetDisplayRules = Plugin;

})();
(function()
{
	// Widget Form

	jQuery( window ).load( function()
	{
		var template = wp.template( 'wdc-settings' );

		// Button click

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

					this.$content
						.widgetDisplayRules(
						{
							conditions : JSON.parse( $conditions.val() )
						})
						.on( 'widgetDisplayRules/submit', function( event, conditions )
						{
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
