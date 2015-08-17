try{
	var flJQ = jQuery.noConflict();
	
	function fl_tbl_rule_edit( el ) {
		el = flJQ(el); id = el.attr('data-id');
		data = {'sfl_query':'frm_rule_edit', 'sfl_id':id};
		flJQ.ajax({
			dataType: 'html', type: 'post', data: data, url:'/wp-admin/admin-ajax.php',
			success: function( res ) {
				flJQ('#sfl_frm_modal').empty();
				flJQ('#sfl_frm_modal').html( res );
				fl_showFrmModal(true);
			}
		});
	}
	
	function fl_tbl_rule_del( el ) {
		if( confirm('Удалить правило?') ) {
			el = flJQ(el); id = el.attr('data-id');
			data = {'sfl_query':'item_delete', 'sfl_type':'rule', 'sfl_id': id};
			flJQ.ajax({
				dataType: 'json', type:'post', data: data, url:'/wp-admin/admin-ajax.php',
				success: function( json ) {
					if( json.status ) {
						fl_rule_refresh();
					} else {
						if( json.txt ) {
							alert(json.txt);
						} else {
							alert('Ошибка!');
						}
					}
				}
			});
		}
	}
	function fl_tbl_rule_pos( el, type ) {
		if( !el || !type ) return false;
		el = flJQ(el); pos = el.attr('data-pos');
		data = {'sfl_query':'rule_change_position', 'sfl_direction': type, 'sfl_position': pos};
		flJQ.ajax({
			dataType: 'json', type:'post', data: data, url:'/wp-admin/admin-ajax.php',
			success: function( json ) {
				if( json.status ) {
					fl_rule_refresh();
				} else {
					if( json.txt ) {
						alert(json.txt);
					} else {
						alert('Ошибка!');
					}
				}
			}
		});
	}
	
	function fl_frmRuleAdd(el) {
		var data = flJQ('#frm_rule_add [name]').serializeArray();
		var errorBlock = flJQ('#sfl_frm_error');
		var pagesCnt = flJQ('#sfl_frm_rule_add_pages').children().length;
		var linksCnt = flJQ('#sfl_frm_rule_add_links').children().length;
		if( data.length && pagesCnt && linksCnt ) {
			data.push({'name':'sfl_query', 'value':'rule_add'});
			flJQ.ajax({
				dataType: 'json', type:'post', data: data, url:'/wp-admin/admin-ajax.php',
				beforeSend: function() {
					flJQ('#sfl_wait_result').show();
					errorBlock.hide();
				},
				success: function( json ) {
					if( json.status ) {
						fl_rule_refresh();
						fl_showFrmModal(false);
					} else {
						if( json.txt ) {
							errTxt = json.txt;
							flJQ.each(errTxt, function(i, text) {
								el = flJQ('<div>');
								el.text( text );
								errorBlock.append( el );
							});
							errorBlock.show();
						} else {
							alert('Ошибка!');
						}
					}
				},
				complete: function() {
					flJQ('#sfl_wait_result').hide();
				}
			});
		} else {
			alert('Заполните правило!');
		}
	}
	
	function fl_frmRuleEdit( el ) {
		var ruleID = flJQ('#frm_rule_add').attr('data-id');
		if( !ruleID ) {
			alert('ОШИБКА: ID правила не верный!');
			return false;
		}
		var vals = [];
		var flds = flJQ('#frm_rule_add [name]');
		vals = flds.serializeArray();
		vals.push( { 'name': 'sfl_rule[id]', 'value': ruleID } );
		/*flJQ.each(flds, function(i,v) {
			v = flJQ(v); name = v.attr('name'); val = v.val(); type = v.parents().attr('data-type');
			if( type ) name = name.replace(/^([^\[]+)/i, '$1_new');
			vals.push( {'name': name, 'value': val } );
		});*/
		
		var errorBlock = flJQ('#sfl_frm_error');
		var pagesCnt = flJQ('#sfl_frm_rule_add_pages').children().length;
		var linksCnt = flJQ('#sfl_frm_rule_add_links').children().length;
		if( vals.length && pagesCnt && linksCnt ) {
			vals.push( {'name':'sfl_query', 'value':'rule_edit'} );
			
			flJQ.ajax({
				dataType: 'json', type:'post', data: vals, url:'/wp-admin/admin-ajax.php',
				beforeSend: function() {
					flJQ('#sfl_wait_result').show();
					errorBlock.hide();
				},
				success: function( json ) {
					if( json.status ) {
						fl_rule_refresh();
						fl_showFrmModal(false);
					} else {
						if( json.txt ) {
							errTxt = json.txt;
							flJQ.each(errTxt, function(i, text) {
								el = flJQ('<div>');
								el.text( text );
								errorBlock.append( el );
							});
							errorBlock.show();
						} else {
							alert('Ошибка!');
						}
					}
				},
				complete: function() {
					flJQ('#sfl_wait_result').hide();
				}
			});
		} else {
			alert('Заполните правило!');
		}
	}
	
	function fl_frmRuleAdd_pageAdd( el, type ) {
		if( !type ) type = 'add';
		el = flJQ(el); tbl = el.parents('.sfl-frm-row').children('.sfl-frm-tbl'); id = parseInt( tbl.children().last().attr('data-id') ) * 1;
		flJQ.ajax({
			dataType: 'html', type:'post', data:{'sfl_query':'frm_rule_page_item', 'sfl_id':id + 1, 'sfl_type':type}, url:'/wp-admin/admin-ajax.php',
			success: function(res) {
				tbl.append( res );
			}
		});
	}
	function fl_frmRuleAdd_pageRemove(el) {
		el = flJQ(el); tbl = el.parents('.sfl-frm-tbl-row');
		tbl.remove();
		
	}
	function fl_frmRuleAdd_linkRemove(el) {
		el = flJQ(el); tbl = el.parents('.sfl-frm-tbl-row');
		tbl.remove();
		
	}
	function fl_frmRuleAdd_linkmetaRemove(el) {
		el = flJQ(el); tbl = el.parents('.sfl-frm-tbl-meta-row');
		tbl.remove();
		
	}
	
	
	function fl_frmRuleEdit_itemRemove( el, type ) {
		if( !el && !type ) return false;
		
		var el = flJQ(el); var elClass = ''; var msg = 'Удалить?';
		switch( type ) {
			case 'page' : elClass = '.sfl-frm-tbl-row'; msg = 'Удалить страницу?'; break;
			case 'link' : elClass = '.sfl-frm-tbl-row'; msg = 'Удалить ссылку?'; break;
			case 'linkmeta': elClass = '.sfl-frm-tbl-meta-row'; msg = 'Удалить мета-данные?'; break;
		}
		tbl = el.parents( elClass );
		id = tbl.attr('data-id');
		if( confirm( msg ) ) {
			data = {'sfl_query':'item_delete', 'sfl_type':type, 'sfl_id': id};
			flJQ.ajax({
				dataType: 'json', type:'post', data: data, url:'/wp-admin/admin-ajax.php',
				success: function( json ) {
					if( json.status ) {
						tbl.remove();
						fl_rule_refresh();
					} else {
						if( json.txt ) {
							alert(json.txt);
						} else {
							alert('Ошибка!');
						}
					}
				}
			});
		}
	}
	
	function fl_frmRuleAdd_linkAdd( el, type ) {
		if( !type ) type = 'add';
		el = flJQ(el); tbl = el.parents('.sfl-frm-row').children('.sfl-frm-tbl'); id = parseInt( tbl.children().last().attr('data-id') ) * 1;
		flJQ.ajax({
			dataType: 'html', type:'post', data:{'sfl_query':'frm_rule_link_item', 'sfl_id':id + 1, 'sfl_type': type}, url:'/wp-admin/admin-ajax.php',
			success: function(res) {
				tbl.append( res );
			}
		});
	}
	function fl_frmRuleAdd_linkRemove(el) {
		el = flJQ(el); tbl = el.parents('.sfl-frm-tbl-row');
		tbl.remove();
		
	}
	
	function fl_frmRuleAdd_linkmetaAdd( el, type ) {
		if( !type ) type = 'add';
		el = flJQ(el);
		tbl = el.parents('.sfl-frm-tbl-row').children('.sfl-frm-tbl-meta');
		idLink = parseInt( tbl.parents('.sfl-frm-tbl-row').attr('data-id') );
		id = parseInt( tbl.children().last().attr('data-id') ) * 1;
		flJQ.ajax({
			dataType: 'html', type:'post', data:{'sfl_query':'frm_rule_linkmeta_item', 'sfl_id_link':idLink, 'sfl_id':id + 1, 'sfl_type':type}, url:'/wp-admin/admin-ajax.php',
			success: function(res) {
				tbl.append( res );
			}
		});
	}
	function fl_frmRuleAdd_linkmetaRemove(el) {
		el = flJQ(el); tbl = el.parents('.sfl-frm-tbl-meta-row');
		tbl.remove();
		
	}
	
	function fl_showFrmModal(show) {
		if( show ) {
			flJQ('#sfl_overlay').fadeIn(200, function() {
				flJQ('#sfl_frm_modal').fadeIn(200);
			});
		} else {
			flJQ('#sfl_frm_modal').fadeOut(200, function() {
				flJQ('#sfl_overlay').fadeOut(200);
			}).empty();
		}
	}
	
	function fl_rule_add() {
		flJQ.ajax({
			dataType: 'html', type:'post', data:{'sfl_query':'frm_rule_add'}, url:'/wp-admin/admin-ajax.php',
			success: function( res ) {
				flJQ('#sfl_frm_modal').empty();
				flJQ('#sfl_frm_modal').html( res );
				fl_showFrmModal(true);
			}
		});
	}
	function fl_rule_refresh() {
		var body = flJQ('#sfl_tbl_rules');
		flJQ.ajax({
			dataType: 'html', type:'post', data:{'sfl_query':'tbl_rules'}, url:'/wp-admin/admin-ajax.php',
			success: function( result ) {
				body.empty();
				body.html( result );
			}
		});
	}
	
	function flDocReady() {
		flJQ('#fl_btn_rule_add').click( fl_rule_add );
		flJQ('#fl_btn_rule_refresh').click( fl_rule_refresh );
		fl_rule_refresh();
	}
	
	flJQ(document).ready(flDocReady);
} catch(e) {
	console.log('ERROR [FL_JS_BACK]: ' + e);
}