begin
declare cur no scroll cursor for select distinct az, id, kaufdatum from udt_calc_saldo order by id;
    
    declare nHF WBETRAG;
    declare nGF WBETRAG;
    declare cAZ char(12);
    declare cID integer;
    declare ckd date;
    declare i integer;

    declare err_notfound exception for sqlstate value '02000';
    
    set i=1;
    set nHF=0;
    open cur with hold;
    
    AzLoop: loop
    fetch next cur into cAz, cID, ckd;
    if sqlstate = err_notfound then
      leave AzLoop
    end if;    

    set nHF = FCAdmin.AkteSummiereRestHF(caz,ckd);
    set nGF = fcadmin.AkteGibRestforderung(caz, 0,ckd);
    
    message STRING(i,' ',cID ,'  ',cAz,' ', nHF, ' ' ,nGF) type info to client;
    UPDATE udt_calc_saldo set kHF = nHF, kGF = nGF WHERE az = cAZ;
    commit;
    set i = i+1;
    end loop AzLoop;
    close cur;
    
    commit work
end
