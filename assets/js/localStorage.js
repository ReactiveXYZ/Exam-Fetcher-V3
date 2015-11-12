/**
 * Local Storage Category Options
 * @type {Object}
 */
var localStorageCategories = {
    RECENTS: "recents",
    FAVS: "favourites"
}

/**
 * Remove items from localStorage
 * @param  {String} category  name of the category
 * @param  {String} item_name name of the item in the category
 * @return {void}           
 */
function removeItemInLocalStorage (category,item_name) {

	var generatedArray = [];

    if (category == localStorageCategories.RECENTS){
        var localRecArray = JSON.parse(localStorage['recents']);

        for (var i = 0; i < localRecArray.length; i ++){
            if (item_name == localRecArray[i]){
                localRecArray.splice(i,1);
                break;
            }
        }
        generatedArray = JSON.stringify(localRecArray);

        localStorage['recents'] = generatedArray;
    }

    if (category == localStorageCategories.FAVS){

        var localFavArray = JSON.parse(localStorage['favourites']);
        for (var j = 0; j < localFavArray.length; j ++){
            console.log(item_name + " =? " + localFavArray[j]);
            if (item_name == localFavArray[j]){
                console.log("matched?");
                localFavArray.splice(j,1);
                break;
            }
        }
        generatedArray = JSON.stringify(localFavArray);
        localStorage['favourites'] = generatedArray;
    }

    refreshUIFromLocalStorage();

}

/**
 * Update quickaccess item UI from LocalStorage
 */
function refreshUIFromLocalStorage () {
	var placeholder_array = [];
    //Check recents
    if (localStorage['recents'] && JSON.parse(localStorage['recents']).length>0){
        //Get data
        var recents_array = JSON.parse(localStorage['recents']);
        //Push to HTML
        var tag_ul_re = document.createElement('ul');

        var count;

        if (recents_array.length >=6){

            count = recents_array.length - 5;

        }else{
            count = 0;
        }

        for (var i = recents_array.length-1; i >= count; i --){

            var tag_li_re = document.createElement('li');
            var tag_a_re = document.createElement('a');
            tag_a_re.className = "clickable-tags";
            var tag_a_span_re = document.createElement('span');
            tag_a_re.appendChild(tag_a_span_re);
            tag_a_re.innerHTML += recents_array[i];
            tag_li_re.appendChild(tag_a_re);
            tag_ul_re.appendChild(tag_li_re);

        }
        //Clear old UI
        document.getElementById('tag-recents').innerHTML = "";
        document.getElementById('tag-recents').appendChild(tag_ul_re);
        document.getElementById('tag-recents').innerHTML+="<br/>";
    }else{

        localStorage['recents'] = JSON.stringify(placeholder_array);
        document.getElementById('tag-recents').innerHTML = "<h6>No Recents Here! Go and make some searches</h6>";
    
    }

    //Check favourites
    if (localStorage['favourites'] && JSON.parse(localStorage['favourites']).length >0 ){
        //Clean
        document.getElementById('tag-favourites').innerHTML = "";
        //Get data
        var favourite_array = JSON.parse(localStorage['favourites']);
        //Push to HTML
        var tag_ul_fa = document.createElement('ul');

        for (var j = 0; j < favourite_array.length; j ++){
            var tag_li_fa = document.createElement('li');
            var tag_a_fa = document.createElement('a');
            tag_a_fa.className = "clickable-tags";
            var tag_a_span_fa = document.createElement('span');
            tag_a_fa.appendChild(tag_a_span_fa);
            tag_a_fa.innerHTML += favourite_array[j];
            tag_li_fa.appendChild(tag_a_fa);
            tag_ul_fa.appendChild(tag_li_fa);
        }
        //Clear old UI
        document.getElementById('tag-favourites').innerHTML = "";

        document.getElementById('tag-favourites').appendChild(tag_ul_fa);
    
    }else{

        localStorage['favourites'] = JSON.stringify(placeholder_array);
        
        document.getElementById('tag-favourites').innerHTML = "<h6>Hey, add some now to the favourite panel!</h6>"
    
    }
}

/**
 * Add recent search items to localStorage
 */
function addToRecentsBySubmit () {

	var limit = 6; var generatedArray = []; var currentRecents = JSON.parse(localStorage['recents']);
    
    // In single mode
    if (mode == modeSet.SINGLE){
        if (fieldSet + currentRecents.length > limit){
            //exceeding limit
            for (var i =0; i < fieldSet; i ++){
                currentRecents.push($('#field_div_id_'+i+'_subject').val());
            }
            for (var j = currentRecents.length-1; j > currentRecents.length-limit-1; j --){
                generatedArray.push(currentRecents[i]);
            }
        }else{
            //under limit
            for (var i =0; i < fieldSet; i ++){
                currentRecents.push($('#field_div_id_'+i+'_subject').val());
            }
            generatedArray = currentRecents;
        }
    // In bulk mode
    }else if(mode == modeSet.BULK){

        if (getBulkData().length + currentRecents.length > limit){

            //exceeding the limit
            for (var m = 0; m < getBulkData().length ; m++ ){
                currentRecents.push(getBulkData()[m]);
            }
            
            for (var n = currentRecents.length-1; n > currentRecents.length-limit-1; n --){
                generatedArray.push(currentRecents[n]);
            }

        }else{
            //under limit
            for (var m = 0; m < getBulkData().length ; m++ ){
                currentRecents.push(getBulkData()[m]);
            }
            generatedArray = currentRecents;
        }
    }

    localStorage['recents'] = JSON.stringify(generatedArray);
}

/**
 * Convert the string in bulk textfield to array
 * @return {[type]} [description]
 */
function getBulkData () {
	//Get value
    var value = $('input[type="hidden"][name="bulk_subject"]').val();

    var needsToBeCleaned = ["[","]","\""];

    for (var i = 0; i < needsToBeCleaned.length; i ++){

        needsToBeCleaned = str_replace(needsToBeCleaned[i],'',value);

    }
    
    return needsToBeCleaned.split(',');
}





