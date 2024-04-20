// 'use strict';

// https://legacy.reactjs.org/docs/add-react-to-a-website.html
// Add React to a Website
// <p>This page demonstrates using React with no build tooling.</p>
// <p>React is loaded as a script tag.</p>

// import * as React from 'react'
// import ReactDOM from 'https://unpkg.com/react-dom@16/umd/react-dom.production.min.js';

const JSON_HEADERS = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
};

let current_project = {
    "p_id": 0,
    "p_name": "---",
    "p_tasks_count": 0
}

let current_task = {
    "t_id": 286,
    "p_id": 1,
    "t_priority": 4,
    "t_date": "2024-02-12 03:34:16",
    "t_subject": "t 1 4",
    "t_comments": "------"
}

function Loader() {

    React.useEffect(() => windowOnLoad());

    return "";
}

render(<Loader/>, "loader")

function windowOnLoad() {
    fetchWhoIAm()
    fetchProjects()
}

const RawHtml = ({rawHtml}) => {
    const __html = html => ({
        // === panedrone: wrap it just to get rid of ide warnings
        __html: html, // https://stackoverflow.com/questions/29044518/safe-alternative-to-dangerouslysetinnerhtml
    });
    return (
        <span dangerouslySetInnerHTML={__html(rawHtml)}></span>
    )
}

function fetchWhoIAm() {
    fetch("api/whoiam")
        .then(async (resp) => {
            if (resp.status === 200) {
                let res = await resp.text()
                if (!res) {
                    res = '== unknown =='
                }
                render(<RawHtml rawHtml={res}/>, 'who-I-am')
            } else {
                let j = await resp.text()
                showServerError(resp.status + " " + j);
            }
        })
        .catch((reason) => {
            showServerError(reason)
        })
}

function fetchProjects() {
    fetch("api/projects")
        .then(async (resp) => {
            if (resp.status === 200) {
                let res = await resp.json()
                if (!res) {
                    res = []
                }
                render(<ProjectDetails data={res}/>, 'projects')
            } else {
                let j = await resp.text()
                showServerError(resp.status + " " + j);
            }
        })
        .catch((reason) => {
            showServerError(reason)
        })
}

class FieldState {

    constructor(initial, onChange, saveUpdater, isValid = null) {

        // === panedrone: "useState" allows to implement "FieldState" without inheritance from "Component"

        [this.value, this.setValue] = React.useState(initial)

        if (saveUpdater) {
            saveUpdater(this.setValue)
        }

        this.onChange = onChange
        this.isValid = isValid

        // === panedrone: "bind" allows to use "this" inside of methods

        this.handleChange = this.handleChange.bind(this);
        this.getValue = this.getValue.bind(this);
        this.setValue = this.setValue.bind(this);
    }

    handleChange(event) {
        let targetValue = event.target.value
        if (this.isValid) {
            let valid = this.isValid(targetValue)
            if (!valid) {
                return
            }
        }
        if (this.onChange) {
            this.onChange(targetValue);
        }
        this.setValue(targetValue);
    }

    getValue() {
        return this.value
    }

    setValue(value) {
        this.value = value
    }
}

const StringField = ({initial, onChange, saveUpdater}) => {

    const state = new FieldState(initial, onChange, saveUpdater)

    // <p>
    //     <strong>Current value:</strong>
    //     {state.getValue() || '(empty)'}
    // </p>

    return (
        <label>
            <input value={state.getValue()} onChange={state.handleChange}/>
        </label>
    )
}

const IntegerField = ({initial, onChange, saveUpdater}) => {

    function isInteger(value) {

        // === panedrone: "value" is always a string

        if (!value) {
            return true // === panedrone: allow typing from scratch
        }

        let parsed = parseInt(value)
        let equal = parsed.toString() === value

        return parsed && parsed <= 10 && equal
    }

    const state = new FieldState(initial, onChange, saveUpdater, isInteger)

    // === panedrone: it is buggy because it allows typing not numerical strings without triggering "onChange"

    // return (
    //     <label>
    //         <input type="number" min="1" max="10" pattern="[0-9\s]" value={state.getValue()} onChange={state.handleChange}/>
    //     </label>
    // )

    return (
        <label>
            <input pattern="[0-9\s]" value={state.getValue()} onChange={state.handleChange}/>
        </label>
    )
}

const MultilineStringField = ({initial, onChange, saveUpdater}) => {

    const state = new FieldState(initial, onChange, saveUpdater)

    return (
        <label>
            <textarea cols="40" rows="10" value={state.getValue()} onChange={state.handleChange}></textarea>
        </label>
    )
}

function fetchProjectTasks(p_id) {
    // console.log(p_id.toString());
    fetch("api/projects/" + p_id + "/tasks")
        .then(async (resp) => {
            if (resp.status === 200) {
                setVisibleProjectDetails(true)
                let res = await resp.json()
                if (!res) {
                    res = []
                }
                render(<ProjectTasks data={res}/>, 'tasks')
            } else {
                let j = await resp.text()
                alert(resp.status + " " + j);
            }
        })
        .catch((reason) => {
            console.log(reason)
        })
}

const ProjectDetails = ({data}) => {

    function fetchCurrentProject(p_id) {
        fetch("api/projects/" + p_id)
            .then(async (resp) => {
                if (resp.status === 200) {
                    current_project = await resp.json()
                    if (current_project) {
                        updateCurrentProjectName(current_project.p_name)
                    }
                } else {
                    let j = await resp.text()
                    showServerError(resp.status + " " + j);
                }
            })
            .catch((reason) => {
                showServerError(reason)
            })
    }

    function fetchProjectData(project) {
        setVisibleTaskForm(false)
        fetchCurrentProject(project.p_id)
        fetchProjectTasks(project.p_id)
    }

    return data.map((project) => {
            return (
                <tr>
                    <td onClick={() => fetchProjectData(project)}>
                        <a>{project.p_name}</a>
                    </td>
                    <td className="center w1">
                        {project.p_tasks_count}
                    </td>
                </tr>
            )
        }
    )
}

const TaskTitle = ({initial}) => {
    return <span>{initial}</span>
}

function fetchTask(t_id) {
    let t_id_s = t_id.toString()
    fetch("api/tasks/" + t_id_s)
        .then(async (resp) => {
            if (resp.status === 200) {
                setVisibleTaskForm(true)
                current_task = await resp.json()
                if (!current_task) {
                    return
                }
                render(<TaskTitle initial={current_task.t_subject}/>, 'subj')
                updateSubject(current_task.t_subject)
                updateDate(current_task.t_date)
                updatePriority(current_task.t_priority)
                updateComments(current_task.t_comments)
            } else {
                let j = await resp.text()
                showServerError(resp.status + " " + j);
            }
        })
        .catch((reason) => {
            showServerError(reason)
        })
}

const ProjectTasks = ({data}) => {

    return data.map((task) => {
            return (
                <tr>
                    <td className="w1">
                        {task.t_date}
                    </td>
                    <td onClick={() => fetchTask(task.t_id)}>
                        <a>{task.t_subject}</a>
                    </td>
                    <td className="center">
                        {task.t_priority}
                    </td>
                </tr>
            )
        }
    )
}

function CreateProjectButton() {

    function handleClick(_) {
        if (new_project_name.length === 0) {
            new_project_name = '?'
        }
        let json = JSON.stringify({"p_name": new_project_name})
        fetch("api/projects", {
            method: 'post',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 201) {
                    fetchProjects();
                } else {
                    let j = await resp.text()
                    showServerError(resp.status + " " + j);
                }
            })
            .catch((reason) => {
                showServerError(reason)
            })
    }

    return (
        <a href="#">
            <input type="button" value="+" onClick={handleClick}/>
        </a>
    )
}

const CreateTaskButton = () => {

    function handleClick(_) {
        let p_id = current_project.p_id
        let json = JSON.stringify({"t_subject": new_task_subject})
        fetch("api/projects/" + p_id + "/tasks", {
            method: 'post',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 201) {
                    fetchProjects();
                    fetchProjectTasks(p_id); // update tasks count
                } else {
                    let text = await resp.text()
                    showServerError(resp.status + " " + text);
                }
            })
            .catch((reason) => {
                showServerError(reason)
            })
    }

    return (
        <a href="#">
            <input type="button" value="+" onClick={handleClick}/>
        </a>
    )
}

const ProjectButtons = () => {

    function projectUpdate(_) {
        let p_id = current_project.p_id
        let json = JSON.stringify(current_project)
        fetch("api/projects/" + p_id, {
            method: 'put',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 200) {
                    fetchProjects();
                } else {
                    let j = await resp.text()
                    showServerError(resp.status + " " + j);
                }
            })
            .catch((reason) => {
                showServerError(reason)
            })
    }

    function projectDelete(_) {
        let p_id = current_project.p_id
        fetch("api/projects/" + p_id, {
            method: 'delete'
        })
            .then(async (resp) => {
                if (resp.status === 204) {
                    setVisibleProjectDetails(false)
                    fetchProjects();
                } else {
                    let j = await resp.text()
                    showServerError(resp.status + " " + j);
                }
            })
            .catch((reason) => {
                showServerError(reason)
            })
    }

    function hideProjectDetails(_) {
        setVisibleProjectDetails(false)
    }

    return (
        <table className="controls">
            <tbody>
            <tr>
                <td id="curr_project">
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&#x2713;" onClick={projectUpdate}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="x" onClick={projectDelete}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&lt;" onClick={hideProjectDetails}/>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    )
}

const TaskButtons = () => {

    function taskUpdate(_) {
        if (!isNaN(current_task.t_priority)) {
            current_task.t_priority = parseInt(current_task.t_priority);
            if (!current_task.t_priority) {
                current_task.t_priority = 1
            }
        }
        let json = JSON.stringify(current_task)
        let p_id = current_project.p_id
        let t_id_s = current_task.t_id.toString()
        fetch("api/tasks/" + t_id_s, {
            method: 'put',
            headers: JSON_HEADERS,
            body: json
        })
            .then(async (resp) => {
                if (resp.status === 200) {
                    fetchProjectTasks(p_id);
                    fetchTask(current_task.t_id);
                    hideTaskError()
                } else {
                    let msg = await resp.text()
                    showTaskError(resp.status, msg)
                }
            })
            .catch((reason) => {
                showServerError(reason)
            })
    }

    function taskDelete(_) {
        let p_id = current_project.p_id
        let t_id = current_task.t_id
        fetch("api/tasks/" + t_id, {
            method: "delete"
        })
            .then(async (resp) => {
                if (resp.status === 204) {
                    fetchProjects(); // update tasks count
                    fetchProjectTasks(p_id);
                    hideTaskError()
                    setVisibleTaskForm(false)
                } else {
                    let text = await resp.text()
                    showServerError(resp.status + " " + text);
                }
            })
            .catch((reason) => {
                showServerError(reason)
            })
    }

    function hideTaskDetails(_) {
        setVisibleTaskForm(false)
    }

    return (
        <table className="controls">
            <tbody>
            <tr>
                <td className="w100">
                    <div className="title" id="subj">
                    </div>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&#x2713;" onClick={taskUpdate}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="x" onClick={taskDelete}/>
                    </a>
                </td>
                <td className="w1">
                    <a href="#">
                        <input type="button" value="&lt;" onClick={hideTaskDetails}/>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    )
}

const ErrorArea = ({initial, saveUpdater}) => {

    const state = new FieldState(initial, null, saveUpdater)

    return (
        <div>
            {state.getValue().length > 0 && <p>
                <button onClick={(_)=>state.setValue("")}>&#x2713;</button>
                &nbsp;
                <strong>Error:</strong>&nbsp;{state.getValue()}
            </p>}
        </div>
    );
}

// https://stackoverflow.com/questions/17267329/converting-unicode-character-to-string-format

function unicodeToChar(text) {
    return text.replace(/\\u[\dA-F]{4}/gi,
        function (match) {
            return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
        });
}

let updateServerError = null

const serverError = <ErrorArea initial={""} saveUpdater={(updater) => {
    updateServerError = updater
}}/>

render(serverError, 'serverError');

function showServerError(msg) {
    msg = unicodeToChar(msg);
    msg = msg.replace(/\\"/g, '"');
    console.log(msg)
    updateServerError(msg)
}

let updateTaskError = null

const taskError = <ErrorArea initial={""} saveUpdater={(updater) => {
    updateTaskError = updater
}}/>

render(taskError, 'taskError');

function hideTaskError() {
    updateTaskError("")
}

function showTaskError(code, msg) {
    msg = unicodeToChar(msg);
    // https://stackoverflow.com/questions/6640382/how-to-remove-backslash-escaping-from-a-javascript-var
    msg = msg.replace(/\\\\"/g, '"');
    msg = msg.replace(/\\"/g, '"');
    msg = code.toString() + " " + msg
    updateTaskError(msg)
}

function setVisibleProjectDetails(yes) {
    if (yes) {
        elementById('projectDetails').style.display = "table-cell"; // to show
    } else {
        elementById('projectDetails').style.display = "none"; // to hide
        setVisibleTaskForm(false)
    }
}

function setVisibleTaskForm(yes) {
    hideTaskError()
    if (yes) {
        elementById('taskForm').style.display = "table-cell"; // to show
        // elementById('taskForm').style.display = "block"; // to show
    } else {
        elementById('taskForm').style.display = "none"; // to hide
    }
}

function elementById(id) {
    return document.getElementById(id)
}

function render(component, containerID) {
    ReactDOM.render(component, elementById(containerID))
}

//////////////////////////////////////////////////////////////////////////

let new_project_name = ""

render(<StringField onChange={v => {
    new_project_name = v
}}/>, 'new_project_name')

let new_task_subject = ""

render(<StringField onChange={v => {
    new_task_subject = v
}}/>, 'new_task_subject')

render(<CreateProjectButton/>, 'projectCreate')
render(<CreateTaskButton/>, 'taskCreate')

render(<ProjectButtons/>, 'projectActions')
render(<TaskButtons/>, 'taskActions')

let updateCurrentProjectName = null

let fieldCurrentProjectName = <StringField initial="" onChange={v => {
    current_project.p_name = v
}} saveUpdater={(updater) => {
    updateCurrentProjectName = updater
}}/>

render(fieldCurrentProjectName, 'curr_project')

let updateSubject = null

let fieldSubject = <StringField initial="" onChange={v => {
    current_task.t_subject = v
}} saveUpdater={(updater) => {
    updateSubject = updater
}}/>

render(fieldSubject, 't_subject')

let updateDate = null

let fieldDate = <StringField initial="" onChange={v => {
    current_task.t_date = v
}} saveUpdater={(updater) => {
    updateDate = updater
}}/>

render(fieldDate, 't_date')

let updatePriority = null

let fieldPriority = <IntegerField initial="" onChange={v => {
    current_task.t_priority = v
}} saveUpdater={(updater) => {
    updatePriority = updater
}}/>

render(fieldPriority, 't_priority')

let updateComments = null

let areaComments = < MultilineStringField initial="" onChange={v => {
    current_task.t_comments = v
}} saveUpdater={(updater) => {
    updateComments = updater
}}/>

render(areaComments, 't_comments')