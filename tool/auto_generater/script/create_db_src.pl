#!/usr/bin/perl

use strict;
use Jcode ;
use utf8;
use Text::CSV_XS;
use Switch;

binmode(STDOUT, ":utf8");

my $g_base_path = "./";
my $g_temp_path = $g_base_path . "template/";
my $g_temp_dbo_path = $g_temp_path . "dbo/";
my $g_temp_dbsvc_path = $g_temp_path . "dbsvc/";
my $g_temp_dbd_path = $g_temp_path . "dbdata/";

my $g_output_path = $g_base_path . "output/";
my $g_output_dbo_path = $g_output_path . "dbo/";
my $g_output_dbsvc_path = $g_output_path . "dbsvc/";
my $g_output_dbd_path = $g_output_path . "dbdata/";

my $g_dboCore_File = "temp_dboCore.txt";
my $g_dboTable_File = "temp_dboTable.txt";
my $g_dbsvcTable_File = "temp_dbsvcTable.txt";
my $g_dbdTable_File = "temp_dbdTable.txt";

my $g_Output_dboCore = "dboCore.php";
my $g_Output_dboTable = "dbo{0}.php";
my $g_Output_dbsvcTable = "dbsvc{0}.php";
my $g_Output_dbdTable = "dbd{0}.php";

my $g_target = "○";
my $g_key_target = "●";
my $g_dbl_target = "◎";

my $TABLE_NAME_INDEX = 2;
my $TABLE_DATA_INDEX = 7;

sub	readTable
{
	my ($path) = @_;

	my	%cells ;
	my	$c ;

	my $csv = Text::CSV_XS->new({ binary=>1 });

	open( $c, $path ) || return undef ;
	binmode( $c, ":utf8" ) ;
	my $index = 0;
	while( my $columns = $csv->getline ($c) ) {
		$cells{$index}{'NO'} = @$columns[0] ;
		$cells{$index}{'NAME'} = @$columns[1] ;
		$cells{$index}{'FIELD'} = @$columns[2] ;
		$cells{$index}{'TYPE'} = @$columns[3] ;
		$cells{$index}{'SIZE'} = @$columns[4] ;
		$cells{$index}{'ACC'} = @$columns[5] ;
		$cells{$index}{'PK'} = @$columns[6] ;
		$cells{$index}{'FK'} = @$columns[7] ;
		$cells{$index}{'FK_TABLE'} = @$columns[8] ;
		$cells{$index}{'FK_NAME'} = @$columns[9] ;
		$cells{$index}{'UK'} = @$columns[10] ;
		$cells{$index}{'IDX'} = @$columns[11] ;
		$cells{$index}{'NULL'} = @$columns[12] ;
		$cells{$index}{'DEFAULT'} = @$columns[13] ;
		$cells{$index}{'AUTO_NO'} = @$columns[14] ;
		$cells{$index}{'CHAR'} = @$columns[15] ;
		$cells{$index}{'CHAR2'} = @$columns[16] ;
		$cells{$index}{'NOTE'} = @$columns[17] ;
		$cells{$index}{'NOTE2'} = @$columns[18] ;
		$cells{$index}{'NOTE3'} = @$columns[19] ;
		$cells{$index}{'NOTE4'} = @$columns[20] ;
		$cells{$index}{'CORE'} = @$columns[21] ;
		$cells{$index}{'INS'} = @$columns[22] ;
		$cells{$index}{'UPD'} = @$columns[23] ;
		$cells{$index}{'DEL'} = @$columns[24] ;
		$cells{$index}{'HARD_DEL'} = @$columns[25] ;
		$cells{$index}{'GET'} = @$columns[26] ;
		$cells{$index}{'SELECT'} = @$columns[27] ;
		$index++;
	}
	close( $c ) ;

	return \%cells ;
}

sub	readTemplate
{
	my ($path) = @_;
	my	$c ;
	my $readdata;

	open( $c, $path ) || return undef ;
	binmode( $c, ":utf8" ) ;
	local $/ = undef; 
	$readdata = readline $c;

	close( $c ) ;
	return $readdata;
}

sub	execute
{
	my ( $file_name ) = @_ ;
	my $i;

	# CSVデータの読み込み
	my $cells = readTable($file_name);

#	my $keycount = keys(%$cells);
#	for($i = 0; $i < $keycount; $i++) {
#		print qq!value = $cells->{$i}{'CORE'}\n!;
#	}


	my $dboCoreTmp = readTemplate($g_temp_dbo_path.$g_dboCore_File);
	my $dboTableTmp = readTemplate($g_temp_dbo_path.$g_dboTable_File);
	my $dbsvcTableTmp = readTemplate($g_temp_dbsvc_path.$g_dbsvcTable_File);
	my $dbdTableTmp = readTemplate($g_temp_dbd_path.$g_dbdTable_File);

#	print qq!$dboCoreTmp!;

	createdboCore($cells, $dboCoreTmp);
	createdboTable($cells, $dboTableTmp);
	createdbsvcTable($cells, $dbsvcTableTmp);
	createdbdTable($cells, $dbdTableTmp);

#	saveProcedure($output_path, $table_name, $temp);
}

sub	createdboCore() {
	my ( $cells, $template ) = @_ ;

	my $value = createConstParam($cells, $g_target);
	my $member = createMemberParam($cells, $g_target);

	$template =~ s/\{0\}/$value/g;
	$template =~ s/\{1\}/$member/g;

#print qq!$template\n!;

	saveDboCore($template);
}

sub     createdboTable() {
	my ( $cells, $template ) = @_ ;

	my $table = getTableName($cells);
	my $class = getClassName($table);
	my $value = createConstParam($cells, "");
	my $member = createMemberParam($cells, "");
	my $ins_item = createItemList($cells, 'INS');
	my $ins_key = createKeyList($cells, 'INS');
	my $upd_item = createItemList($cells, 'UPD');
	my $upd_key = createKeyList($cells, 'UPD');
	my $del_item = createItemList($cells, 'DEL');
	my $del_key = createKeyList($cells, 'DEL');
	my $hard_item = createItemList($cells, 'HARD_DEL');
	my $hard_key = createKeyList($cells, 'HARD_DEL');
	my $get_item = createItemList($cells, 'GET');
	my $get_key = createKeyList($cells, 'GET');
	my $select_item = createItemList($cells, 'SELECT');
	my $select_key = createKeyList($cells, 'SELECT');

	$template =~ s/\{0\}/$class/g;
	$template =~ s/\{1\}/$value/g;
	$template =~ s/\{2\}/$member/g;
	$template =~ s/\{3\}/$ins_item/g;
	$template =~ s/\{4\}/$ins_key/g;
	$template =~ s/\{5\}/$upd_item/g;
	$template =~ s/\{6\}/$upd_key/g;
	$template =~ s/\{7\}/$del_item/g;
	$template =~ s/\{8\}/$del_key/g;
	$template =~ s/\{9\}/$hard_item/g;
	$template =~ s/\{10\}/$hard_key/g;
	$template =~ s/\{11\}/$get_item/g;
	$template =~ s/\{12\}/$get_key/g;
	$template =~ s/\{13\}/$select_item/g;
	$template =~ s/\{14\}/$select_key/g;

#print qq!$template\n!;

	saveDboTable($template, $class);
}

sub	createdbsvcTable() {
	my ( $cells, $template ) = @_ ;

	my $table = getTableName($cells);
	my $class = getClassName($table);

	$template =~ s/\{0\}/$class/g;
	$template =~ s/\{1\}/$table/g;

#print qq!$template\n!;

	saveDbsvcTable($template, $class);
}

sub	createdbdTable() {
	my ( $cells, $template ) = @_ ;

	my $table = getTableName($cells);
	my $class = getClassName($table);
	my $const = createFieldList($cells);

	$template =~ s/\{0\}/$class/g;
	$template =~ s/\{1\}/$const/g;

#print qq!$template\n!;

	saveDbdTable($template, $class);
}

sub	getTableName() {
	my ( $cells ) = @_ ;
	my $table = $cells->{$TABLE_NAME_INDEX}{'TYPE'};
	print qq!tablename = $table\n!;
	return $table;
}

sub	getClassName() {
	my ( $table_name ) = @_ ;
	my $class = ucfirst($table_name);
	return $class;
}

sub     createConstParam() {
	my ( $cells, $target ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $template = "	const M_{0}	= \"{1}\";	// {2}\n";
	my $value = "";

	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		if ($cells->{$i}{'CORE'} eq $target) {
			my $work = $template;
			my $constName = uc($cells->{$i}{'FIELD'});
			$work =~ s/\{0\}/$constName/;
			$work =~ s/\{1\}/$cells->{$i}{'FIELD'}/;
			$work =~ s/\{2\}/$cells->{$i}{'NAME'}/;
			$value.=$work;
		}
	}
	return  $value;
}

sub	createMemberParam() {
	my ( $cells, $target ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $template = "	public \$m_{0};	// {1}\n";
	my $value = "";

	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		if ($cells->{$i}{'CORE'} eq $target) {
			my $work = $template;
			my $constName = $cells->{$i}{'FIELD'};
			$work =~ s/\{0\}/$constName/;
			$work =~ s/\{1\}/$cells->{$i}{'NAME'}/;
			$value.=$work;
		}
	}
	return  $value;
}

sub	createItemList() {
	my ( $cells, $field_name ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $template = "\$this::{0}";
	my $value = "";
	my @index_list;
	my $item_count = 0;

	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		if ($cells->{$i}{$field_name} eq $g_target || $cells->{$i}{$field_name} eq $g_dbl_target) {
			$index_list[$item_count] = $i;
			$item_count++;
		}
	}

	for($i = 0; $i < $item_count; $i++) {
		my $index = $index_list[$i];
		my $work = $template;
		my $constName = "M_".uc($cells->{$index}{'FIELD'});
		$work =~ s/\{0\}/$constName/;
		if ($i > 0) {
			$value.="		      ";
		}
		$value.=$work;
		if ($i != $item_count-1) {
			$value.=",\n";
		}
	}
	return  $value;
}

sub	createKeyList() {
	my ( $cells, $field_name ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $template = "\$this::{0}";
	my $value = "";
	my @index_list;
	my $item_count = 0;

	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		if ($cells->{$i}{$field_name} eq $g_key_target || $cells->{$i}{$field_name} eq $g_dbl_target) {
			$index_list[$item_count] = $i;
			$item_count++;
		}
	}

	for($i = 0; $i < $item_count; $i++) {
		my $index = $index_list[$i];
		my $work = $template;
		my $constName = "M_".uc($cells->{$index}{'FIELD'});
		$work =~ s/\{0\}/$constName/;
		if ($i > 0) {
			$value.="		     ";
		}
		$value.=$work;
		if ($i != $item_count-1) {
			$value.=",\n";
		}
	}

	return  $value;
}

sub	createFieldList() {
	my ( $cells ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $template = "	const DBD_{0}	= \"{1}\";	// {2}\n";
	my $value = "";

	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		my $work = $template;
		my $constName = uc($cells->{$i}{'FIELD'});
		$work =~ s/\{0\}/$constName/;
		$work =~ s/\{1\}/$cells->{$i}{'FIELD'}/;
		$work =~ s/\{2\}/$cells->{$i}{'NAME'}/;
		$value.=$work;
	}

	return  $value;
}

sub	saveDboCore() {
	my ( $data ) = @_ ;
	my $f;
	my $file_name = $g_output_dbo_path.$g_Output_dboCore;

	open( $f, " > $file_name" ) || return undef ;
	binmode( $f, ":utf8" ) ;

	printf( $f "%s", $data ) ;

	close($f);
}

sub	saveDboTable() {
	my ( $data, $table_name ) = @_ ;
	my $f;
	my $file = $g_Output_dboTable;
	$file =~ s/\{0\}/$table_name/;
	my $file_name = $g_output_dbo_path.$file;

	open( $f, " > $file_name" ) || return undef ;
	binmode( $f, ":utf8" ) ;

	printf( $f "%s", $data ) ;

	close($f);
}

sub	saveDbsvcTable() {
	my ( $data, $table_name ) = @_ ;
	my $f;
	my $file = $g_Output_dbsvcTable;
	$file =~ s/\{0\}/$table_name/;
	my $file_name = $g_output_dbsvc_path.$file;

	open( $f, " > $file_name" ) || return undef ;
	binmode( $f, ":utf8" ) ;

	printf( $f "%s", $data ) ;

	close($f);
}

sub	saveDbdTable() {
	my ( $data, $table_name ) = @_ ;
	my $f;
	my $file = $g_Output_dbdTable;
	$file =~ s/\{0\}/$table_name/;
	my $file_name = $g_output_dbd_path.$file;

	open( $f, " > $file_name" ) || return undef ;
	binmode( $f, ":utf8" ) ;

	printf( $f "%s", $data ) ;

	close($f);
}

die "Usage : csv_file_name\n" if ( @ARGV < 1 ) ;

my	$file_name = $ARGV[0] ;

execute($file_name);

exit 0 ;
