let xhttp = new XMLHttpRequest();

let loginForm = $("#loginForm");
let userForm = $("#userForm");
let eventForm = $("#eventForm");

let logoutBtn = $("#logoutBtn");
let createEventBtn = $("#createEventBtn");
let createUserBtn = $("#createUserBtn");
let displayBtn = $("#displayBtn");
let displayBtnUser = $("#displayBtnUser");
let showBookedEventsBtn = $("#showBookedEventsBtn");

let createEventSec = $("#createEventSec");
let createUserSec = $("#createUserSec");
let displayEventsSec = $("#displayEventsSec");
let eventsTotalLen = 1;
let bookedEventsTotal = 1; 
let nonBookedEventsTotal = 1; 

let currAction = "";
let currActionDel = "delete";
let currActionUpd = "update";

function login(e) {
    let url = "../src/php/login_action.php";
    let data = loginForm.serialize();
    let res;
    $.ajax({
        type : "GET",
        url : url,
        data : data,
        success : function(response) {
            res = JSON.parse(response);
            alert(res["message"]);
            if(res["status"] == 1) {
                userType = res["userType"];
                userType == "Admin" ? location.replace("../public/admin.php") : location.replace("../public/user.php");
            }
        }
    });
    e.preventDefault();
}

function logout(e) {
    let url = "../src/php/logout_action.php";
    let res;
    $.ajax({
        type : "GET",
        url : url,
        success: function(response) {
            alert(response);
            location.replace("../public/login.php");
        }
    })
}

function register(e) {
    let url = "../src/php/create-user_action.php";
    let data = $("#userForm").serialize();
    let res;
    xhttp.open("POST", url+"?"+data, true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            res = JSON.parse(this.responseText);
            alert(res["message"]);
            if(res["status"] == 1) {
                $("#userForm")[0].reset();
            }
        }
    }
    e.preventDefault();
}

function addEvent(e) {
    let url = "../src/php/create-event_action.php";
    let data = new FormData(this);
    let res;
    $.ajax({
        url : url,
        data : data,
        type : "POST",
        contentType: false,
        cache: false,
        processData: false,
        success : function(response) {
            res = JSON.parse(response);
            if(res["status"] == 1) {
                eventForm[0].reset();
            }
            alert(res["message"]);
        }
    });
    e.preventDefault();
}

function showUserBtn() {
    createEventSec.addClass("hide");
    createUserSec.removeClass("hide");
    displayEventsSec.addClass("hide");
}

function showEventBtn() {
    createEventSec.removeClass("hide");
    createUserSec.addClass("hide");
    displayEventsSec.addClass("hide");
}

function showEventDisplay(type="book") {
    let url = "../src/php/get-events_action.php";
    let nonbookArr = [];
    let bookArr = [];
    let adminArr = [];
    let x = eventsTotalLen;
    let y = bookedEventsTotal;
    let z = nonBookedEventsTotal;
    $(".nonbook").each((index, elem) => {
        nonbookArr.push($(elem).attr("data-id"));
    });
    $(".book").each((index, elem) => {
        bookArr.push($(elem).attr("data-id"));
    });
    $(".event").each((index, elem) => {
        adminArr.push($(elem).attr("data-id"));
    });
    let nonbookArrStr = "nonbookArr=" + JSON.stringify(nonbookArr);
    let bookArrStr = "bookArr=" + JSON.stringify(bookArr);
    let adminArrStr = "adminArr=" + JSON.stringify(adminArr);
    let typeStr = `type=${type}`;
    let data = `${nonbookArrStr}&${bookArrStr}&${typeStr}&${adminArrStr}`;
    $.ajax({
        url : url,
        data : data,
        type : "GET",
        success : function(data) {
            data = JSON.parse(data);
            userType = data[0];
            if(userType == "Admin") {
                if(data.length > eventsTotalLen) {
                    for( ; x < data.length; x++) {
                        displayEventsSec.append(createEventDisplayBlockAdmin(data[x]["event_name"], data[x]["event_image"], data[x]["event_id"]));

                        displayEventsSec.find(".event:last-of-type").find(".delBtn").on("click", (e)=> {
                            let userAccCont = getUserActCont(e);
                            let userPromptCont = getPromptCont(e);
                            currAction = currActionDel;
                            showDelPrompt(userAccCont, userPromptCont);
                        });
                        
                        displayEventsSec.find(".event:last-of-type").find(".updBtn").on("click", (e)=> {
                            let userAccCont = getUserActCont(e);
                            let userPromptCont = getPromptCont(e);
                            currAction = currActionUpd;
                            showDelPrompt(userAccCont, userPromptCont);
                        });

                        displayEventsSec.find(".event:last-of-type").find(".yesBtn").on("click", (e)=> {
                            let eventCont = $(e.target).parent().parent();
                            if(currAction == currActionDel) deleteEvent(eventCont);
                            if(currAction == currActionUpd) {
                                let userprompt = prompt("Enter new event name: ");
                                if(userprompt) updateEvent(eventCont, userprompt);
                            }
                        });
                        displayEventsSec.find(".event:last-of-type").find(".noBtn").on("click", (e)=> {
                            let userAccCont = getUserActCont(e);
                            let userPromptCont = getPromptCont(e);
                            showUserActCont(userAccCont, userPromptCont);
                        });
                    }
                    eventsTotalLen = 1;
                }
            }
            if(userType == "User") {
                if(type == "book") {
                    let btnhtml;
                    if(data.length > bookedEventsTotal) {
                        btnhtml = `<button class="cancelBtn">Cancel</button>`;
                        for( ; y < data.length; y++) {
                            displayEventsSec.append(createEventDisplayBlockUser(data[y]["event_name"], data[y]["event_image"], data[y]["booking_id"], "book", btnhtml));

                            displayEventsSec.find(".event:last-of-type").find(".cancelBtn").on("click", (e)=> {
                                let eventCont = $(e.target).parent();
                                let willCancel = confirm("Do you want to cancel this event?");
                                if(willCancel) {
                                    addRemovedBookedEvent(eventCont);
                                    cancelEvent(eventCont)
                                }
                            });
                        }
                        bookedEventsTotal = 1;
                    }
                }
                if(type == "nonbook") {
                    if(data.length > nonBookedEventsTotal) {
                        btnhtml = `<button class="bookBtn">Book</button>`;
                        for( ; z < data.length; z++) {
                            displayEventsSec.append(createEventDisplayBlockUser(data[z]["event_name"], data[z]["event_image"], data[z]["event_id"], "nonbook", btnhtml));

                            displayEventsSec.find(".event:last-of-type").find(".bookBtn").on("click", (e)=> {
                                let eventCont = $(e.target).parent();
                                let willBook = confirm("Do you want to book this event?");
                                if(willBook) bookEvent(eventCont);
                            });
                        }
                        nonBookedEventsTotal = 1;
                    }
                }
            }
        }
    });
}
function createEventDisplayBlockAdmin(eventName, imgUrl, eventID) {
    let img = `
        <img src="${imgUrl}">
    `;
    let h1 = `
        <h1>Event name: <span class="eventName">${eventName}</span></h1>
    `;
    let btnCont = `
        <div class="event__btn-cont event__user-actions">
            <button class="updBtn">Update</button>
            <button class="delBtn">Delete</button>
        </div>
    `;
    let delPrompt = `
        <div class="event__btn-cont event__prompt hide">
            <h2>Do you want to continue?</h2>
            <button class="yesBtn">Yes</button>
            <button class="noBtn">No</button>
        </div>
    `;
    let eventBlock = `<div data-id="${eventID}" class="event">${img}${h1}${btnCont}${delPrompt}</div>`;
    return eventBlock;
}
function createEventDisplayBlockUser(eventName, imgUrl, eventID, classN, buttonHtml) {
    let img = `
        <img src="${imgUrl}">
    `;
    let h1 = `
        <h1>${eventName}</h1>
    `;
    let btn = `
        ${buttonHtml}
    `;
    let eventBlock = `
        <div data-id="${eventID}" class="event ${classN}">
            ${img}
            ${h1}
            ${btn}
        </div>`;
    return eventBlock;
}

function deleteEvent(eventCont) {
    let eventID = $(eventCont).attr("data-id");
    let url = "../src/php/delete-event_action.php";
    let res;
    $.ajax({
        url: url,
        type: "POST",
        data: `eventID=${eventID}`,
        success: function(response) {
            res = JSON.parse(response);
            alert(res["message"]);
            if(res["status"] == 1) {
                $(eventCont).remove();
            }
        }
    })
}

function updateEvent(eventCont, newEventName) {
    let eventNameElem = $(eventCont).find(".eventName");
    let eventID = $(eventCont).attr("data-id");
    let userAccCont = $(eventCont).find(".event__user-actions");
    let userPromptCont = $(eventCont).find(".event__prompt");
    let url = "../src/php/update-event_action.php";
    let res;
    $.ajax({
        url : url,
        data : `eventName=${newEventName}&eventID=${eventID}`,
        type : "POST",
        success : (response) => {
            res = JSON.parse(response);
            alert(res["message"]);
            showUserActCont(userAccCont, userPromptCont);
            if(res["valid"]==1) $(eventNameElem).text(newEventName);
        }
    })
}

function bookEvent(eventCont) {
    let url = "../src/php/book-event_action.php";
    let eventID = $(eventCont).attr("data-id");
    let res;
    $.ajax({
        url : url,
        data : `eventID=${eventID}`,
        type : "POST",
        success: (response) => {
            res = JSON.parse(response);
            alert(res["message"]);
            if(res["valid"]==1) {
                $(eventCont).remove();
            }
        }
    });
}

function cancelEvent(eventCont) {
    let url = "../src/php/cancel-event_action.php";
    let bookingID = $(eventCont).attr("data-id");
    let res;
    $.ajax({
        url: url,
        type : "POST",
        data : `bookingID=${bookingID}`,
        success : function(response) {
            res = JSON.parse(response);
            alert(res["message"]);
            if(res["valid"]==1) {
                $(eventCont).remove();
            } 
        }
    })
}
function addRemovedBookedEvent(eventCont) {
    let url = "../src/php/add-removed-event_action.php";
    let bookingID = $(eventCont).attr("data-id");
    let data = `bookingID=${bookingID}`;
    let res, eventName, imgUrl, eventID, btnhtml;
    $.ajax({
        url : url,
        data : data,
        type : "POST",
        success : function(response) {
            res = JSON.parse(response);
            if(res["valid"]==1) {
                eventName = res["data"]["event_name"];
                imgUrl = res["data"]["event_image"];
                eventID = res["data"]["event_id"];
                btnhtml = `<button class="bookBtn">Book</button>`;

                displayEventsSec.append(createEventDisplayBlockUser(eventName, imgUrl, eventID, "nonbook hide", btnhtml));
                displayEventsSec.find(".event:last-of-type").find(".bookBtn").on("click", (e)=> {
                    let eventCont = $(e.target).parent();
                    let willBook = confirm("Do you want to book this event?");
                    if(willBook) bookEvent(eventCont);
                });
            }
        }
    })
}

function showDelPrompt(userAccCont, userPromptCont) {
    userAccCont.addClass("hide");
    userPromptCont.removeClass("hide");
}
function showUserActCont(userAccCont, userPromptCont) {
    userAccCont.removeClass("hide");
    userPromptCont.addClass("hide");
}
function getUserActCont(e) {
    return $(e.target).parents(".event").find(".event__user-actions");
}
function getPromptCont(e) {
    return $(e.target).parents(".event").find(".event__prompt");
}

loginForm.on("submit", login);
userForm.on("submit", register);
eventForm.on("submit", addEvent);

logoutBtn.on("click", logout);
createUserBtn.on("click", showUserBtn);
createEventBtn.on("click", showEventBtn);
displayBtn.on("click", ()=>{
    createEventSec.addClass("hide");
    createUserSec.addClass("hide");
    displayEventsSec.removeClass("hide");
    showEventDisplay();
});
displayBtnUser.on("click", ()=>{
    $(".book").each(function() {
        $(this).addClass("hide");
    }) 
    $(".nonbook").each(function() {
        $(this).removeClass("hide");
    }) 
    $(displayEventsSec).find("#event").text("Events");
    showEventDisplay("nonbook");
});
showBookedEventsBtn.on("click", ()=>{
    $(".book").each(function() {
        $(this).removeClass("hide");
    }) 
    $(".nonbook").each((index, elem) => {
        $(elem).addClass("hide");
    }) 
    $(displayEventsSec).find("#event").text("Booked Events");
    // $(".nonbook").each(function() {
    //     $(this).addClass("hide");
    // }) 
    showEventDisplay();
});