(function ($) {
    'use strict';
    var fileError = "Please select .xslx/.xml/.json file format";
    $("#team-import-form input[type='file']").on('change', function () {
        var file = $(this)[0].files[0],
            target = $("#team-import-form").find('.total-data-found'),
            reader = new FileReader();
        if (file.type === "application/json") {
            reader.onload = function (event) {
                var obj = JSON.parse(event.target.result);
                console.log(obj);
                var members = obj.members,
                    length = members.length;
                    target.html(length + " Member found to import");
            };
            reader.readAsText(file);
        } else if (file.type === "text/xml") {
            reader.onload = function (event) {
                var x2js = new X2JS();
                var jsonObj = x2js.xml_str2json(event.target.result),
                    members = jsonObj.members.member,
                    length = members.length;
                target.html(length + " Member found to import");
            };
            reader.readAsText(file);
        } else if(file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
            reader.onload = function (event) {
                var data = event.target.result;
                var workbook = XLSX.read(data, {type: 'binary'}),
                    first_sheet_name = workbook.SheetNames[0],worksheet = workbook.Sheets[first_sheet_name],
                    members = XLSX.utils.sheet_to_json(worksheet,{raw:true}),
                    length = members.length;
                target.html(length + " Member found to import");
            };
            reader.readAsBinaryString(file);
        } else {
            $(this).parents('form')[0].reset();
            alert(fileError);
        }
        // console.log(file);
    });
    $("#team-import-form").on('submit', function (e) {
        e.preventDefault();
        var self = $(this),
            file = self.find('input[type=file]')[0].files[0],
            target = $("#team-import-form").find('.total-data-found'),
            reader = new FileReader(),
            importing = '<span class="importing">Importing....</span>';
        if (file.type === "application/json") {
            reader.onload = function (event) {
                var obj = JSON.parse(event.target.result);
                // console.log(obj);
                var members = obj.members,
                    length = members.length;
                if (length) {
                    target.append(importing);
                    self[0].reset();
                    insert_member(members);
                }
            };
            reader.readAsText(file);
        } else if (file.type === "text/xml") {
            reader.onload = function (event) {
                var x2js = new X2JS();
                var jsonObj = x2js.xml_str2json(event.target.result),
                    members = jsonObj.members.member,
                    length = members.length;
                if (length) {
                    target.append(importing);
                    self[0].reset();
                    insert_member(members);
                }
            };
            reader.readAsText(file);
        } else if(file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
            reader.onload = function (event) {
                var data = event.target.result;
                /* if binary string, read with type 'binary' */
                var workbook = XLSX.read(data, {type: 'binary'}),
                    first_sheet_name = workbook.SheetNames[0],worksheet = workbook.Sheets[first_sheet_name],
                    members = XLSX.utils.sheet_to_json(worksheet,{raw:true}),
                    length = members.length;
                if (length) {
                    var newMember = $.map(members, function (member) {
                        member.department = member.department.split(',');
                        member.designation = member.designation.split(',');
                        member.skill = member.skill.split(',');
                        member.social = member.social.split(',');
                        if(member.skill.length){
                            var allSkill=[];
                            $.map(member.skill, function (skill) {
                                var s = skill.split('=>');
                                allSkill.push({'id': s[0].trim(), 'percent': s[1].trim()});
                            });
                            member.skill = allSkill;
                        }
                        if(member.social.length){
                            var allSocial=[];
                            $.map(member.social, function (social) {
                                var s = social.split('=>');
                                allSocial.push({'id': s[0].trim(), 'url': s[1].trim()});
                            });
                            member.social = allSocial;
                        }
                        return member;
                    });
                    target.append(importing);
                    self[0].reset();
                    insert_member(newMember);
                }
            };
            reader.readAsBinaryString(file);
        }  else {
            self[0].reset();
            alert(fileError);
        }
        return false;
    });

    $("#team-export-form").on('submit', function (e) {
        e.preventDefault();
        var data = $(this).serializeArray(),
            newData = [],
            exField = [];
        $.each(data, function (index, field) {
            if(field.name == "export_field[]"){
                exField.push(field.value);
            }
            if(field.name == "export_format"){
                newData.format = field.value
            }
        });
        newData.field = exField;
        $.ajax({
            url: ttp.ajaxurl,
            method: "POST",
            data: {data: newData.field, format: newData.format, action: 'tlp_team_export'},
            beforeSend: function () {

            },
            success: function (data) {
                console.log(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                target.find('.importing').html('ERROR !!!');
                console.log('ERROR', textStatus, errorThrown);
            }
        });
        return false;
    });

    function insert_member(members) {
        var responce = $("#response"),
            target = $("#team-import-form"),
            complete = target.find('.completed-import'),
            length = members.length,
            i = 1;
        $.each(members, function (index, member) {
            $.ajax({
                url: ttp.ajaxurl,
                method: "POST",
                data: {member: member, action: 'team_member_import'},
                success: function (data) {
                    if(i === length){
                        target.find('.importing').remove();
                    }
                    if(!data.error){
                        complete.html("<span>Complete - "+ i +"</span>");
                        i++;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    target.find('.importing').html('ERROR !!!');
                    console.log('ERROR', textStatus, errorThrown);
                }
            });
        });
    }

})(jQuery);