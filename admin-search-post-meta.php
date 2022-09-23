add_action('after_setup_theme', function(){
	add_filter('posts_groupby', function($groupby){
		global $pagenow, $wpdb;
		if(is_admin() and $pagenow === 'edit.php' and is_search()){
			$g = $wpdb->posts . '.ID';
			if(!$groupby){
				$groupby = $g;
			} else {
				$groupby = trim($groupby) . ', ' . $g;
			}
		}
		return $groupby;
	});
	add_filter('posts_join', function($join){
		global $pagenow, $wpdb;
		if(is_admin() and $pagenow === 'edit.php' and is_search()){
			$j = 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id';
			if(!$join){
				$join = $j;
			} else {
				$join = trim($join) . ' ' . $j;
			}
		}
		return $join;
	});
	add_filter('posts_where', function($where){
		global $pagenow, $wpdb;
		if(is_admin() and $pagenow === 'edit.php' and is_search()){
			$s = get_query_var('s');
			$s = $wpdb->esc_like($s);
			$s = '%' . $s . '%';
			$str = '(' . $wpdb->posts . '.post_title LIKE %s)';
			$sql = $wpdb->prepare($str, $s);
			$search = $sql;
			$str = '(' . $wpdb->postmeta . '.meta_value LIKE %s)';
			$sql = $wpdb->prepare($str, $s);
			$replace = $search . ' OR ' . $sql;
			$where = str_replace($search, $replace, $where);
		}
		return $where;
	});
});
