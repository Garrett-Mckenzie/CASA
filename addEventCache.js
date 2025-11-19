var form = null;
(function() {
    form = document.getElementById('new-event-form');
    const KEY = "AddEventData";
    console.log("Form: ", form);

        try{
            const savedData = JSON.parse(localStorage.getItem(KEY));
            if(savedData){
                for(const [key, value] of Object.entries(savedData)){
                    const input = form.elements[key];
                    if(input){
                        input.value = value;
                    }
                }
            }
        }
        catch(e){
            console.error("Error loading saved form data:", e);
        }

    function saveData(){
        const data = {};
        if(form == null){
            form = document.getElementById('new-event-form');
        }
        if(form != null){
            for(const element of form.elements){
                if(element.name){
                    data[element.name] = element.value;
                }
            }

            try{
                localStorage.setItem(KEY, JSON.stringify(data));
            }
            catch(e){
                console.error("Error saving form data:", e);
            }
        }
    }

    function clearCache(){
        localStorage.removeItem(KEY);
    }

    setInterval(saveData, 5000);
})();