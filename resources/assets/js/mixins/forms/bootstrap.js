/**
 * Load the SparkForm helper class.
 */
require('./form');

/**
 * Define the SparkFormError collection class.
 */
require('./errors');

/**
 * Add additional HTTP / form helpers to the Spark object.
 */
$.extend(Jiri, require('./http'));
