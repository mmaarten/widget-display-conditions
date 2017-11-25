(function()
{
	function WDC( elem, options )
	{
		this.$elem   = jQuery( elem );
		this.options = jQuery.extend( {}, WDC.defaultOptions, options );

		var _this = this;

		// Add rule group button click
		this.$elem.on( 'click', '.add-rule-group', function( event )
		{
			event.preventDefault();

			_this.addRuleGroup();
		});

		// Add rule button click
		this.$elem.on( 'click', '.rule .and button', function( event )
		{
			event.preventDefault();

			var $group = jQuery( this ).closest( '.rule-group' );

			_this.addRule( $group.data( 'id' ) );
		});

		// Remove rule button click
		this.$elem.on( 'click', '.rule .remove button', function( event )
		{
			event.preventDefault();

			var $rule = jQuery( this ).closest( '.rule' );

			_this.removeRule( $rule.data( 'id' ) );
		});

		// Rule param change
		this.$elem.on( 'change', '.rule .param select', function( event )
		{
			event.preventDefault();

			var $rule     = jQuery( this ).closest( '.rule' );
			var $param    = $rule.find( '.param select' );
			var $operator = $rule.find( '.operator select' );
			var $value    = $rule.find( '.value select' );

			WDC.loadRule( $param.val(), function( data )
			{
				WDC.populateDropdown( $operator.empty(), data.operators );
				WDC.populateDropdown( $value.empty(), data.choices );
			});
		});

		// Form submit
		this.$elem.on( 'submit', 'form', function( event )
		{
			event.preventDefault();

			// Gets all rules from form

			var data = jQuery( this ).serializeObject(), rules = {};

			if ( data.hasOwnProperty( 'rules' ) ) 
			{
				rules = data.rules;
			}

			// Notifies clients

			_this.$elem.trigger( 'wdc/submit', [ rules ] );
		});

		// Rule Removed
		this.$elem.on( 'wdc/ruleRemoved', function( event, $rule, $group )
		{
			// Removes group when no rules

			if ( ! $group.find( '.rule' ).length ) 
			{
				_this.removeRuleGroup( $group.data( 'id' ) );
			};
		});

		// Rule Added
		this.$elem.on( 'wdc/ruleAdded', function( event, $rule )
		{
			_this.$elem.addClass( 'has-rules' );
		});

		// Rule removed
		this.$elem.on( 'wdc/ruleRemoved', function( event, $rule )
		{
			if ( ! _this.$elem.find( '.rule' ).length ) 
			{
				_this.$elem.removeClass( 'has-rules' );
			}
		});

		// Creates rules
		jQuery.each( this.options.rules, function( groupId, rules )
		{
			_this.addRuleGroup( groupId, rules );
		});

		//

		jQuery( document ).trigger( 'wdc/init', [ this ] );
	}

	WDC.defaultOptions = 
	{
		ajaxurl : window.ajaxurl || '',
		rules   : {}
	};

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

	WDC.rulesLoaded = {};

	WDC.loadRule = function( param, complete )
	{
		// Checks if already loaded

		if ( WDC.rulesLoaded.hasOwnProperty( param ) ) 
		{
			complete( WDC.rulesLoaded[ param ] );

			return;
		};

		//

		jQuery.ajax( 
		{
			url : ajaxurl,
			method : 'POST',
			data : { action : 'wdc_load_rule', param : $param.val() },
			success : function( response )
			{
				WDC.rulesLoaded[ param ] = response.data;

				complete( response.data );
			}
		});
	};

	WDC.prototype.addRuleGroup = function( id, rules ) 
	{
		if ( typeof id === 'undefined' ) 
		{
			id = 'group_' + WDC.generateId();
		};

		// Adds group

		var template = wp.template( 'wdc-rule-group' );
			
		var $group = jQuery( template(
		{
			id : id,
		}));

		this.$elem.find( '.rule-groups' )
			.append( $group );

		this.$elem.trigger( 'wdc/ruleGroupAdded', [ $group ] );

		// Adds rules (1 is required)

		if ( typeof rules === 'object' && Object.keys( rules ).length ) 
		{
			var _this = this;

			jQuery.each( rules, function()
			{
				_this.addRule( id, this );
			});
		}

		else
		{
			this.addRule( id );
		};
	};

	WDC.prototype.removeRuleGroup = function( groupId ) 
	{
		// Removes rule

		var $group = this.$elem.find( '.rule-group' ).filter( function()
		{
			return jQuery( this ).data( 'id' ) == groupId;
		});

		if ( ! $group.length ) 
		{
			return;
		};

		$group.remove();

		this.$elem.trigger( 'wdc/ruleGroupRemoved', [ $group ] );
	};

	WDC.prototype.addRule = function( groupId, data ) 
	{
		var defaults = 
		{
			id       : 'rule_' + WDC.generateId(),
			param    : '',
			operator : '',
			value    : ''
		};

		var rule = jQuery.extend( {}, defaults, data );

		// Gets group

		var $group =this.$elem.find( '.rule-group' ).filter( function()
		{
			return jQuery( this ).data( 'id' ) == groupId;
		});

		if ( ! $group.length ) 
		{
			return;
		}

		// Creates rule element

		var template = wp.template( 'wdc-rule' );
			
		var $rule = jQuery( template(
		{
			id 	  : rule.id,
			group : groupId
		}));

		$group.find( '.rules' )
			.append( $rule );

		// param

		$param = $rule.find( '.param select' );

		if ( rule.param ) 
		{
			$param.val( rule.param );
		}

		else
		{
			$param.find( 'option:eq(0)' ).prop( 'selected', true );
		};

		// operator

		$operator = $rule.find( '.operator select' );

		if ( rule.operator )
		{
			$operator.val( rule.operator );
		}

		else
		{
			$operator.find( 'option:eq(0)' ).prop( 'selected', true );
		};

		// value

		$value = $rule.find( '.value select' );

		

		//

		WDC.loadRule( $param.val(), function( data )
		{
			WDC.populateDropdown( $operator.empty(), data.operators, rule.operator );
			WDC.populateDropdown( $value.empty(), data.choices, rule.value );

			$value.select2(
			{
				templateSelection: function( item )
				{
					var $option = jQuery( item.element );

					// Removes hierarchical prefix

					var text = item.text.replace( /–+ ?/g, '' );

					// Makes sure it is trimmed

					text = jQuery.trim( text );

					// Adds option group label

					var $optgroup = $option.closest( 'optgroup' );

					if ( $optgroup.length ) 
					{
						$elem = jQuery( '<span><span class="wdc-tag"></span><span class="wdc-text"></span></span>' );

						$elem.find( '.wdc-tag' ).text( $optgroup.attr( 'label' ) );
						$elem.find( '.wdc-text' ).text( text );

						return $elem;
					};


					return text;
				}
			});
		});

		this.$elem.trigger( 'wdc/ruleAdded', [ $rule ] );
	};

	WDC.prototype.removeRule = function( ruleId ) 
	{
		// Removes rule

		var $rule = this.$elem.find( '.rule' ).filter( function()
		{
			return jQuery( this ).data( 'id' ) == ruleId;
		});

		if ( ! $rule.length ) 
		{
			return;
		};

		var $group = $rule.closest( '.rule-group' );

		$rule.remove();

		this.$elem.trigger( 'wdc/ruleRemoved', [ $rule, $group ] );
	};

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

	window.widgetDisplayConditions = WDC;

})();
(function()
{
	// Widget form

	jQuery( window ).load( function()
	{
		var template = wp.template( 'wdc-settings' );

		// Settings button click

		jQuery( document ).on( 'click', '.widget .wdc-settings-button', function()
		{
			var $widget = jQuery( this ).closest( '.widget' );

			var $rules = $widget.find( '.wdc-rules' );

			var $settings = jQuery( template() );

			// Opens settings page

			jQuery.featherlight( $settings, 
			{
				namespace : 'wdc-modal',
				afterContent : function()
				{
					var modal = this;

					this.$content
						.widgetDisplayConditions(
						{
							rules : JSON.parse( $rules.val() )
						})
						.on( 'wdc/submit', function( event, rules )
						{
							// Stores rules in widget field.
							// Trigger change so user can click the save button.

							$rules
								.val( JSON.stringify( rules ) )
								.trigger( 'change' );

							modal.close();
						});
				}
			});
		});
	});

})();
