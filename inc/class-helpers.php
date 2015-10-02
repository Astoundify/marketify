<?php

class Marketify_Helpers {

    public function __construct() {
        add_filter( 'user_contactmethods', array( $this, 'user_contactmethods' ), 10, 2 );
    }

    public function apply_date_query( $query_args, $timeframe ) {
        if ( 'all' == $timeframe ) {
            return $query_args;
        }

        if ( 'day' == $timeframe ) {
            $frame = date( 'd' );
        } else if ( 'week' == $timeframe ) {
            $frame = date( 'W' );
        } else if ( 'month' == $timeframe ) {
            $frame = date( 'm' );
        } elseif ( 'year' == $timeframe ) {
            $frame = date( 'Y' );
        }

        $query_args[ 'date_query' ][] = array( $timeframe => $frame );

        return $query_args;
    }

    public function user_contactmethods( $methods, $user ) {
        unset( $methods[ 'aim' ] );
        unset( $methods[ 'yim' ] );
        unset( $methods[ 'jabber' ] );

        $methods[ 'twitter' ]  = 'Twitter URL';
        $methods[ 'facebook' ] = 'Facebook URL';
        $methods[ 'gplus' ]    = 'Google+ URL';

        return $methods;
    }
}
