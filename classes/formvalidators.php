<?php

namespace Habari;

/**
 * FormValidators Class
 *
 * Extend this class to supply your own validators, by default we supply most common
 */
class FormValidators
{

	/**
	 * A validation function that returns an error if the value passed in is not a valid URL.
	 *
	 * @param string $text A string to test if it is a valid URL
	 * @param FormControl $control The control that defines the value
	 * @param FormContainer $form The container that holds the control
	 * @param string $warning An optional error message
	 * @return array An empty array if the string is a valid URL, or an array with strings describing the errors
	 */
	public static function validate_url( $text, $control, $form, $warning = null, $schemes = array( 'http', 'https' ) )
	{
		if ( ! empty( $text ) ) {
			$parsed = InputFilter::parse_url( $text );
			if ( $parsed['is_relative'] ) {
				// guess if they meant to use an absolute link
				$parsed = InputFilter::parse_url( 'http://' . $text );
				if ( $parsed['is_error'] ) {
					// disallow relative URLs
					$warning = empty( $warning ) ? _t( 'Relative urls are not allowed' ) : $warning;
					return array( $warning );
				}
			}
			if ( $parsed['is_pseudo'] || ! in_array( $parsed['scheme'], $schemes ) ) {
				// allow only http(s) URLs
				$warning = empty( $warning ) ? _t( 'Only %s urls are allowed', array( Format::and_list( $schemes ) ) ) : $warning;
				return array( $warning );
			}
		}
		return array();
	}

	/**
	 * A validation function that returns an error if the value passed in is not a valid Email Address,
	 * as per RFC2822 and RFC2821.
	 *
	 * @param string $text A string to test if it is a valid Email Address
	 * @param FormControl $control The control that defines the value
	 * @param FormContainer $form The container that holds the control
	 * @param string $warning An optional error message
	 * @return array An empty array if the string is a valid Email Address, or an array with strings describing the errors
	 */
	public static function validate_email( $text, $control, $form, $warning = null )
	{
		if ( ! empty( $text ) ) {
			if ( !preg_match( "@^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*\@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$@i", $text ) ) {
				$warning = empty( $warning ) ? _t( 'Value must be a valid Email Address.' ) : $warning;
				return array( $warning );
			}
		}
		return array();
	}

	/**
	 * A validation function that returns an error if the value passed in is not set.
	 *
	 * @param string $text A value to test if it is empty
	 * @param FormControl $control The control that defines the value
	 * @param FormContainer $form The container that holds the control
	 * @param string $warning An optional error message
	 * @return array An empty array if the value exists, or an array with strings describing the errors
	 */
	public static function validate_required( $value, $control, $form, $warning = null )
	{
		if ( empty( $value ) || $value == '' ) {
			$warning = empty( $warning ) ? _t( 'A value for this field is required.' ) : $warning;
			return array( $warning );
		}
		return array();
	}

	/**
	 * A validation function that returns an error if the the passed username is unavailable
	 *
	 * @param string $text A value to test as username
	 * @param FormControl $control The control that defines the value
	 * @param FormContainer $form The container that holds the control
	 * @param string $allowed_name An optional name which overrides the check and is always allowed
	 * @param string $warning An optional error message
	 * @return array An empty array if the value exists, or an array with strings describing the errors
	 */
	public static function validate_username( $value, $control, $form, $allowed_name = null, $warning = null )
	{
		if ( isset( $allowed_name ) && ( $value == $allowed_name ) ) {
			return array();
		}
		if ( User::get_by_name( $value ) ) {
			$warning = empty( $warning ) ? _t( 'That username is already in use!' ) : $warning;
			return array( $warning );
		}
		return array();
	}


	/**
	 * A validation function that returns an error if the passed control values do not match
	 *
	 * @param string $text A value to test for similarity
	 * @param FormControl $control The control that defines the value
	 * @param FormContainer $form The container that holds the control
	 * @param FormControl $matcher The control which should have a matching value
	 * @param string $warning An optional error message
	 * @return array An empty array if the value exists, or an array with strings describing the errors
	 */
	public static function validate_same( $value, $control, $form, $matcher, $warning = null )
	{
		if ( $value != $matcher->value ) {
			$warning = empty( $warning ) ? _t( 'The value of this field must match the value of %s.', array( $matcher->caption ) ) : $warning;
			return array( $warning );
		}
		return array();
	}

	/**
	 * A validation function that returns an error if the value passed does not match the regex specified.
	 *
	 * @param string $value A value to test if it is empty
	 * @param FormControl $control The control that defines the value
	 * @param FormContainer $container The container that holds the control
	 * @param string $regex The regular expression to test against
	 * @param string $warning An optional error message
	 * @return array An empty array if the value exists, or an array with strings describing the errors
	 */
	public static function validate_regex( $value, $control, $container, $regex, $warning = null )
	{
		if ( preg_match( $regex, $value ) ) {
			return array();
		}
		else {
			if ( $warning == null ) {
				$warning = _t( 'The value does not meet submission requirements' );
			}
			return array( $warning );
		}
	}

	/**
	 * A validation function that returns an error if the value passed is not within a specified range
	 *
	 * @param string $value A value to test if it is empty
	 * @param FormControl $control The control that defines the value
	 * @param FormContainer $container The container that holds the control
	 * @param float $min The minimum value, inclusive
	 * @param float $max The maximum value, inclusive
	 * @param string $warning An optional error message
	 * @return array An empty array if the value is value, or an array with strings describing the errors
	 */
	public static function validate_range( $value, $control, $container, $min, $max, $warning = null )
	{
		if ( $value < $min ) {
			if ( $warning == null ) {
				$warning = _t( 'The value entered is lesser than the minimum of %d.', array( $min ) );
			}
			return array( $warning );
		}
		elseif ( $value > $max ) {
			if ( $warning == null ) {
				$warning = _t( 'The value entered is greater than the maximum of %d.', array( $max ) );
			}
			return array( $warning );
		}
		else {
			return array();
		}
	}
}

?>