#!/usr/bin/perl

use strict;
use Jcode ;
use utf8;
use Text::CSV_XS;
use Switch;

binmode(STDOUT, ":utf8");

my $g_base_path = "./";
my $g_temp_path = $g_base_path . "template/";

my $g_output_path = $g_base_path . "output/";

my $g_Template_File = "temp_table.txt";

my $g_Output_Table = "create_table_{0}.sql";

my $g_not_null = "○";

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

sub	createTableSQL
{
	my ( $cells, $template ) = @_ ;

	my $table = getTableName($cells);
	my $column = createColumnData($cells);
	my $pk = createPK($table, $cells);
	my $fk = createFK($table, $cells);
	my $unique = createUNIQUE($table, $cells);

	$template =~ s/\{0\}/$table/g;
	$template =~ s/\{1\}/$column/g;
	$template =~ s/\{2\}/$pk/g;
	$template =~ s/\{3\}/$fk/g;
	$template =~ s/\{4\}/$unique/g;

#	print qq!$template!;

	saveTableSchema($template, $table);
}

sub	getTableName() {
	my ( $cells ) = @_ ;
	my $table = $cells->{$TABLE_NAME_INDEX}{'TYPE'};
#	print qq!tablename = $table\n!;
	return $table;
}

sub	createColumnData() {
	my ( $cells ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $temp = "{0}	{1}\n";
	my $ret_data = "";

	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		my $type = $cells->{$i}{'TYPE'};
		my $size = $cells->{$i}{'SIZE'};
		my $pk = $cells->{$i}{'PK'};
		my $not_null = $cells->{$i}{'NULL'};
		my $default = $cells->{$i}{'DEFAULT'};

		my $db_type = "";
		if (uc($type) eq 'NUMBER') {
			if ($pk ne '') {
				if ($size >=16) {
					$db_type = "BIGSERIAL";
				}
				else {
					$db_type = "SERIAL";
				}
			}
			else {
				if ($size >=16) {
					$db_type = "BIGINT";
				}
				else {
					$db_type = "INTEGER";
				}
			}
		}
		elsif (uc($type) eq 'VARCHAR2') {
			$db_type = "VARCHAR(".$size.")";
		}
		elsif (uc($type) eq 'DATE') {
			$db_type = "TIMESTAMP";
		}

		if ($not_null eq $g_not_null) {
			$db_type .= '	NOT NULL';
		}

		if ($default ne '') {
			$db_type .= '   DEFAULT '.$default;
		}

		$db_type .= '	-- '.$cells->{$i}{'NAME'};

		my $work = $temp;
		$work =~ s/\{0\}/$cells->{$i}{'FIELD'}/g;
		$work =~ s/\{1\}/$db_type/g;

		if ($i > $TABLE_DATA_INDEX) {
			$ret_data.= '	,'
		}
		$ret_data.= $work;
	}
	return $ret_data;
}

sub	createPK() {
	my ( $table_name, $cells ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $temp = ", constraint {0}_pkey primary key ( {1} )";

	my $ret_data = "";

	my $pk_count = 0;
	my $pk_names = "";
	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		my $pk = $cells->{$i}{'PK'};
		if ($pk ne '') {
			if ($pk_count > 0) {
				$pk_names .= ',';
			}
			$pk_names .= $cells->{$i}{'FIELD'};
			$pk_count++;
		}
	}

	my $work = $temp;
	$work =~ s/\{0\}/$table_name/g;
	$work =~ s/\{1\}/$pk_names/g;
	$ret_data.= $work;

	return $ret_data;
}

sub	createFK() {
	my ( $table_name, $cells ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $temp = ", constraint {0}_fkey{1} foreign key ({2}) references {3} ({4}) MATCH FULL";

	my $ret_data = "";

	my $fk_count = 0;
	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		my $fk = $cells->{$i}{'FK'};
		if ($fk ne '') {
			my $work = $temp;
			$work =~ s/\{0\}/$table_name/g;
			$work =~ s/\{1\}/$fk/g;
			$work =~ s/\{2\}/$cells->{$i}{'FIELD'}/g;
			$work =~ s/\{3\}/$cells->{$i}{'FK_TABLE'}/g;
			$work =~ s/\{4\}/$cells->{$i}{'FK_NAME'}/g;
			$ret_data.= $work;
			if ($i != $keycount - 1) {
				$ret_data.= '\n';
			}
			$fk_count++;
		}
	}
	return $ret_data;
}

sub	createUNIQUE() {
	my ( $table_name, $cells ) = @_ ;
	my $i;
	my $keycount = keys(%$cells);
	my $temp = ", constraint {0}_unique{1} UNIQUE({2})";

	my $ret_data = "";

	my $uk_data = "";
	my $item_count = 0;
	my $uk_count = 0;
	my $data_count = 1;
	for($i = $TABLE_DATA_INDEX; $i < $keycount; $i++) {
		my $uk = $cells->{$i}{'UK'};
		if ($uk ne '') {
			if ($uk == 1 && $item_count > 0) {
				my $work = $temp;
				$work =~ s/\{0\}/$table_name/g;
				$work =~ s/\{1\}/$data_count/g;
				$work =~ s/\{2\}/$uk_data/g;
				$ret_data.= $work."\n";

				$uk_data = "";
				$uk_count = 0;
				$data_count++;
			}

			if ($uk_count > 0) {
				$uk_data .= ',';
			}
			$uk_data .= $cells->{$i}{'FIELD'};

			$item_count++;
			$uk_count++;
		}
	}

	if ($uk_data ne '') {
		my $work = $temp;
		$work =~ s/\{0\}/$table_name/g;
		$work =~ s/\{1\}/$data_count/g;
		$work =~ s/\{2\}/$uk_data/g;
		$ret_data.= $work;
	}

	return $ret_data;
}

sub	saveTableSchema() {
	my ( $data, $table_name ) = @_ ;
	my $f;
	my $file = $g_Output_Table;
	$file =~ s/\{0\}/$table_name/;
	my $file_name = $g_output_path.$file;

	open( $f, " > $file_name" ) || return undef ;
	binmode( $f, ":utf8" ) ;

	printf( $f "%s", $data ) ;

	close($f);
}

sub	execute
{
	my ( $file_name ) = @_ ;
	my $i;

	# CSVデータの読み込み
	my $cells = readTable($file_name);

	my $tableTmp = readTemplate($g_temp_path.$g_Template_File);

#	print qq!$tableTmp!;

	createTableSQL($cells, $tableTmp);
}

die "Usage : csv_file_name\n" if ( @ARGV < 1 ) ;

my	$file_name = $ARGV[0] ;

execute($file_name);

exit 0 ;
