<?php 

namespace MY_THEME\inc;
/**
 * Allow editors to access Gravity Forms
 */
function gravity_forms_roles() {
	$role = get_role( 'editor' );
	$role->add_cap( 'gform_full_access' );
}
add_action( 'admin_init',  __NAMESPACE__ . '\gravity_forms_roles' );
