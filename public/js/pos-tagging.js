var sentences = []; //all sentences
var total_sentence_number = 0; //total number of sentences
var sentences_id = []; //id of the sentences
var total_tag_number = 0;
var tags = []; //all tags
var tags_id = []; //id of all the tags
var word_ptr = 0; // the word which is pointed

var sentence_ptr = 0;
var sentence_information = [];
var color_tags = ["#ff0000", '#0000ff', '#ff0066', '#ffff00', '#80ffcc', '#66ff33', '#ff3333'];



function bring_sentence() {
    $.ajax({
        url: "api/sentences",
        method: "get",
        dataType: "json",
        data: {},
        success: function(data) {
            console.log(data['sentence_count']);
            total_sentence_number = parseInt(data.sentence_count);
            total_tag_number = parseInt(data.tag_count);
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    if (key == "sentences") {
                        var json_sentence = data[key];
                        for (var line in json_sentence) {
                            //alert(json_sentence[line]);
                            sentences_id.push(line);
                            sentences.push(json_sentence[line]);
                        }
                    } else if (key == "tags") {
                        var json_sentence = data[key];
                        for (var line in json_sentence) {
                            //alert(json_sentence[line]);
                            tags_id.push(line);
                            tags.push(json_sentence[line]);
                        }
                    }
                }
            }
            //debug: alert("done");
            init_sentence_information(); //calling dictionary init for each sentence
            make_tag_buttons();
            word_ptr = 0;
            sentence_ptr = 0;
            show_sentence(0);
        }
    });
}

bring_sentence();


function change_text_of_next_button(index) {
    if (index == (total_sentence_number - 1)) {
        //there are no sentence after this, in this pass
        $('#next').html('Finish(Enter)');
    } else {
        //there are sentences,nothing to change
        $('#next').html('Next Sentence(Enter)');
    }
}

function pressed_next_button() {

    var text = $('#next').text();
    if (text == "Next Sentence(Enter)") {
        //sending a sentence response
        sending_sentense_response(sentence_ptr);
        //bring new sentence of the same pass
        if ((sentence_ptr + 1) < total_sentence_number) {
            sentence_ptr++;
            show_sentence(sentence_ptr);
        }
    } else {
        //sending a sentence response
        sending_sentense_response(sentence_ptr);
        //bringing a new sentence set
        sentences = []; //all sentences
        total_sentence_number = 0; //total number of sentences
        sentences_id = []; //id of the sentences
        total_tag_number = 0;
        tags = []; //all tags
        tags_id = []; //id of all the tags

        word_ptr = 0; // the word which is pointed
        sentence_ptr = 0;
        sentence_information = [];

        //confirmation for new set
        var msg = confirm('Do you want to tag another new set?(Y/N)');
        if (msg == true) {
            bring_sentence();
        } else {
            return;
        }
    }
}

function return_tag_id(tag_name) {
    for (var i = 0; i < tags.length; i++) {
        if (tags[i] == tag_name) {
            return tags_id[i];
        }
    }
    return "";
}

function sending_sentense_response(index) {
    var json_object = {};
    var json_subobject = {};
    for (var key in sentence_information[index]) {
        if (sentence_information[index].hasOwnProperty(key) && key != "id") {
            json_subobject[key] = return_tag_id(sentence_information[index][key]);
        }
    }
    json_object[sentence_information[index]["id"]] = json_subobject;

    $.ajax({
        url: "api/sentences",
        method: "post",
        data: {
            value: JSON.stringify(json_object)
        },
        success: function(data) {
            //alert(data);
        }

    });
}

//function is to show sentences
function show_sentence(index) {
    //check what should be writing of next button
    change_text_of_next_button(index);

    //word pointer initiate
    word_ptr = 0;
    if (index < sentences.length) {
        var string = '';
        var temp = sentences[index].split(' ');
        for (var i = 0; i < temp.length; i++) {
            string += '<div>';
            string += '<div class="dropdown">';
            //id: word_0
            var desired_color = return_color_of_tag(sentence_information[index][i]);
            string = string + '<button class="dropbtn" style="color:' + desired_color + '; " id="word_' + i.toString() + '"  ><u>' + temp[i] + '</br></u>' + sentence_information[index][i] + '</button>';
            string += '<div class="dropdown-content">';
            for (var j = 0; j < tags.length; j++) {
                var id_name = "S_" + index + "_W_" + i + "_" + tags[j];
                string = string + '<a href="#" class="tag-options" id="' + id_name + '">' + tags[j] + '</a>';
            }
            string += '</div>';
            string += '</div>';
            string += '</div';
        }

        $('#input-sentence').html(string);
        //pointing word
        pointing_a_word(0);
        //show_buttons(index);
    }
}

function pointing_a_word(word_index) {
    $('#word_' + word_index.toString()).css('background-color', '#ffb3ff');
}

function init_sentence_information() {
    for (var i = 0; i < total_sentence_number; i++) {
        var json_object = {};
        json_object["id"] = sentences_id[i];
        var temp = sentences[i].split(' ');
        for (var j = 0; j < temp.length; j++) {
            json_object[j] = "জানা নেই";
        }
        sentence_information.push(json_object);
    }
}

function return_color_of_tag(tag_name) {
    for (var i = 0; i < tags.length; i++) {
        if (tags[i] == tag_name) return color_tags[i];
    }
    return color_tags[0];
}

function make_tag_buttons() {
    var string = ' <div class="btn-group btn-group-justified" style="width:80%; margin-left:10%; margin-top:10%;">';
    for (var i = 0; i < tags.length; i++) {
        string = string + '<div class="btn-group">';
        string = string + '<button type="button" class="btn btn-default" style=" background:' + color_tags[i] + ';">' + tags[i] + "(" + (i + 1).toString() + ")" + '</button>';
        string = string + '</div>';
    }
    string += '</div>';
    $('#tag-box').html(string);
}

//function is to move_backward word_ptr
function move_backward() {
    if ((word_ptr - 1) >= 0) {
        //previous word exists
        $('#word_' + word_ptr.toString()).css('background-color', '#e6e6e6'); //normal
        word_ptr--;
        pointing_a_word(word_ptr);
    }
}

$(document).on('click', '#backward', function() {
    move_backward();
    return false;
});

//function is to move forward
function move_forward() {
    if ((word_ptr + 1) < sentences[sentence_ptr].split(' ').length) {
        //next word exists
        $('#word_' + word_ptr.toString()).css('background-color', '#e6e6e6'); //normal
        word_ptr++;
        pointing_a_word(word_ptr);
    }
}

$(document).on('click', '#forward', function() {
    move_forward();
    return false;
});

//on next or finish button press
$(document).on('click', '#next', function() {
    pressed_next_button();
    return false;
});

document.addEventListener('keyup', function(event) {

    if (event.defaultPrevented) {
        return;
    }

    var key = event.key || event.keyCode;
    //debug: alert(key);
    //debug: alert(key.toString());
    if (key.toString() == "ArrowLeft") {
        //left arrow,previous word
        move_backward();
    }
    if (key.toString() == "ArrowRight") {
        //right arrow,next word
        move_forward();
    }
    if (key.toString() == "Enter") {
        //right arrow
        pressed_next_button();
    }
    if (parseInt(key) >= 1 && parseInt(key) <= tags.length) {
        //update in sentence_information
        sentence_information[sentence_ptr][word_ptr] = tags[parseInt(key) - 1];
        //change color
        $('#word_' + word_ptr.toString()).css({
            'color': color_tags[parseInt(key) - 1]
        });
        //change writing
        var string = '<u>' + sentences[sentence_ptr].split(' ')[word_ptr] + '</u></br>' + tags[parseInt(key) - 1];
        $('#word_' + word_ptr.toString()).html(string);
        if ((word_ptr + 1) >= 0 && (word_ptr + 1) < sentences[sentence_ptr].split(' ').length) {
            $('#word_' + word_ptr.toString()).css('background-color', '#e6e6e6'); //normal
            word_ptr++;
            pointing_a_word(word_ptr);
        }
    }
});

$(document).on('click', '.tag-options', function() {
    var id = $(this).attr("id");
    //format: S_0_W_0_noun
    var temp = id.split("_");
    var sentence_index = parseInt(temp[1]);
    var word_index = parseInt(temp[3]);
    var selected = temp[4];
    //update in sentence_information
    sentence_information[sentence_index][word_index] = selected;
    //change color
    var color = return_color_of_tag(selected);
    $('#word_' + word_index.toString()).css({
        'color': color
    });
    //change writing
    var string = '<u>' + sentences[sentence_index].split(' ')[word_index] + '</u></br>' + selected;
    $('#word_' + word_index.toString()).html(string);

    //alert("done");

    //now change current word_ptr
    $('#word_' + word_ptr.toString()).css('background-color', '#e6e6e6'); //normal
    word_ptr = word_index;
    pointing_a_word(word_ptr);

});

//button pressing pointer shift
$(document).on('click', '.dropbtn', function() {
    var id = $(this).attr('id');
    var temp = id.split('_');
    var word_idx = parseInt(temp[1]);

    //erasing existing highlight
    $('#word_' + word_ptr.toString()).css('background-color', '#e6e6e6'); //normal
    word_ptr = word_idx;
    pointing_a_word(word_ptr);
    return false;
});