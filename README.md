# cf-ast

mysql -uroot -e "ALTER TABLE asterisk.outbound_routes ADD COLUMN last_index INT DEFAULT 0;"

mysql -uroot -e " ALTER TABLE asterisk.outbound_routes ADD COLUMN type_route INT DEFAULT 0;"

sed -i '/, new ext_set("_NODEST",""));/a $ext->add($context, $exten, "", new ext_set("route_id", $route['route_id']));' /var/www/html/admin/modules/core/functions.inc.php

cd /var/lib/asterisk/agi-bin

git clone https://github.com/vanminh2018/cf-ast.git

cp cf-ast/*.php .

rm -rf cf-ast/

chmod a+x chose_trunk.php

sed -i 's/exten => s,1,Set(DIAL_TRUNK=${ARG1})/exten => s,1,AGI(rr_trunk.php,${route_id})\nexten => s,n,Set(DIAL_TRUNK=${ARG1})/' /etc/asterisk/extensions_override_spec.conf


## TEST:
 
php -r '$argv[1]=20; require_once("rr_trunk.php");'


