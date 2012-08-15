DSNARGS="-h $1"
DATABASE="ritho-web"

/usr/lib/postgresql/9.1/bin/postmaster -h '' -k $1 -D $1 1> $1/pgsql.log < /dev/null 2>&1 &
echo $! > $3/postmaster.pid

while ! psql $DSNARGS -c "select current_timestamp" template1 > /dev/null 2>&1; do
/bin/sleep 1
echo -n "."
done

/usr/bin/createdb $DSNARGS $DATABASE
psql -q -h $1 $DATABASE -f $2/scripts/db/schema-postgresql-0.0.1.sql

echo "To connect: psql -h $1 $database"
