$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

var options = {
    valueNames: [ 'name', 'infos','date' ]
};

var tripList = new List('trip-list', options);

tripList.filter(function(item) {
    if (item.values().isParticipant > 1) {
        return true;
    } else {
        return false;
    }
}); // Only items with id > 1 are shown in list

tripList.filter(); //