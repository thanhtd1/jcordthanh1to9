#!/usr/bin/perl

$dat_path="/var/www/html/jcord/web/stub/";
$log_file= "/var/www/html/jcord/web/stub/log/debug.log";
$inp_file= "/var/www/html/jcord/web/stub/log/input.log";
$out_file= "/var/www/html/jcord/web/stub/log/output.log";

@OUT_HEADER=(
#"Cache-Control: max-age=0, no-cache, no-store, must-revalidate\n",
#"Pragma: no-cache\n",
#"Expires: Wed, 11 Jan 1984 05:00:00 GMT\n",
);

#input===============
_log("=======================================");
_inp();
#====================

_log("REQUEST_URI:".$ENV{"REQUEST_URI"});
@URI= split(/\?/,$ENV{"REQUEST_URI"},-1);

$path= $URI[0];
$param= $URI[1];

$file="default";
if ( substr($path,-1) eq '/' ) {
    $filename = $dat_path . $path . "$file";
} else {
    $filename = $dat_path . $path;
}

#is dir
if ( -d $filename ) {
    $filename .= "/$file";
}


_log("path:".$path);
_log("param:".$param);
_log("filename:".$filename);

$filecheck = $filename . ".json" ;
if ( -f $filecheck ) {
	_log("json:". $filecheck);
	_outFile( "Content-type: application/json\n", $filecheck );
	exit ;
}
if ( -f $filename ) {
	_log("file:". $filename);
	_outFile( "Content-type: text/html\n", $filename );
	exit ;
}

_log("unknown:". $filecheck);
_out( "Content-type: text/html\n","<HTML><BODY>stub</BODY></HTML>\n");

1;

sub _outFile {
    my($head,$fn)= @_;
    my($dat)="";

    open(FD,$fn) ;
    local $/ = undef;
    $dat = <FD>;
    close(FD);

    _out($head,$dat);
}

sub _out {
    my($head,$body)= @_;

    open(OD,">>$out_file") ;
    print(OD _time() . "=======================================\n");

    print(OD $head ) ;
    print( $head ) ;
    print( "Pragma: no-cache\n" );
    print( "Cache-Control: no-cache\n" );
    print( "Expires: Thu, 01 Dec 1994 16:00:00 GMT\n" );
    foreach $h (@OUT_HEADER){
	print( $h ) ;
	print(OD $h ) ;
    }
    print( "\n" ) ;
    print(OD "\n" ) ;

    print(OD $body ) ;
    print( $body ) ;
    close(OD);
}

sub _inp {
    my($dat)="";

    open(ID,">>$inp_file") ;
    print(ID _time() . "=======================================\n");

    foreach $key (sort keys %ENV) {
	print(ID "$key:$ENV{$key}\n");
    }

    if ( $ENV{CONTENT_LENGTH} > 0 ) {
        local $/ = undef;
    	$dat = <STDIN>;
	print(ID "[STDIN]----\n");
	print(ID $dat);
	print(ID "-----------\n");
    }

    close(ID);
}

sub _log {
    my($str)= @_;

    open(LD,">>$log_file") ;
    print(LD _time() . ":" . $str . "\n") ;
    close(LD);
}

sub _time {
    my ($sec, $min, $hour, $mday, $mon, $year, $wday, $yday, $isdst) = localtime;

    $year += 1900;
    $mon ++;
    return "[$year/$mon/$mday $hour:$min:$sec]";
}
