<?php declare(strict_types=1);
/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin.
 */
spl_autoload_register( 'edi_sbpn_namespace_autoload' );

/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin by looking at the $class_name parameter being passed as an argument.
 *
 * The argument should be in the form: EdAc\DLAM\BlogNotifier\Namespace.
 * The function will then break the fully-qualified class name into its pieces and
 * will then build a file to the path based on the namespace.
 *
 * The namespaces in this plugin map to the paths in the directory structure.
 *
 * @param string $class_name The fully-qualified name of the class to load.
 */

function edi_sbpn_namespace_autoload( $class_name ) {

	$base_namespace = 'EdAc\DLAM\BlogNotifier\\';

	// If the specified $class_name does not include our namespace, duck out.
	if ( false === strpos( $class_name, $base_namespace ) ) {
		return;
	}

	$class_name = str_replace( $base_namespace, '', $class_name );

	// Split the class name into an array to read the namespace and class.
	$file_parts = explode( '\\', $class_name );
	//Map the class name to the file name
	$filename = array_pop( $file_parts );
	$filename = strtolower( $filename );

	$filename = "class-$filename.php";

	// Now build a path to the file using mapping to the file location.
	$filepath  = trailingslashit( dirname( __FILE__, 2 ) . '/src/' . implode( '/', $file_parts ) );
	$filepath .= $filename;

	// If the file exists in the specified path, then include it.
	if ( file_exists( $filepath ) ) {
		include_once $filepath;
	} else {
		wp_die(
			esc_html( "The file attempting to be loaded at $filepath does not exist." )
		);
	}
}
