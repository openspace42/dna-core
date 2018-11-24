#!/usr/bin/env bash
dna_completate()
{
    local cur=${COMP_WORDS[COMP_CWORD]}
    tLen=${#COMP_WORDS[@]}
    

    if [ "$tLen" -eq "2" ]; then
        dna_completate_def
    else 
        if [ "${COMP_WORDS[1]}" == "conf" ]; then
            dan_completate_conf_base
        fi
    fi
}

dna_completate_def(){
    COMPREPLY=( $(compgen -W "install build run compile conf help" -- $cur) )
}

dan_completate_conf_base(){
    tLen=${#COMP_WORDS[@]}
    last=$((tLen-1))
   ## echo "\n\n${COMP_WORDS[$(($tLen-2))]} -- ${COMP_WORDS[$last]} -- $(($tLen-1))"
    if [ "${COMP_WORDS[$(($tLen-2))]}" == "add" ]; then
        COMPREPLY=( $(compgen -W "owner node_extend depencies tag" -- $cur) )
    else
    if [ "${COMP_WORDS[$(($tLen-2))]}" == "default" ]; then
            COMPREPLY=( $(compgen -W "help package_name package_version package_uid package_author_grup add package_copyright package_licenseUrl package_require_license_acceptance package_websiteUrl package_docUrl package_release_note package_descripton package_iconUrl package_tag" -- $cur) )
        else
            if [ "${COMP_WORDS[$(($tLen-2))]}" == "package_require_license_acceptance" ]; then
                COMPREPLY=( $(compgen -W "true false" -- $cur) )
            else
                if [ "${COMP_WORDS[$(($tLen-2))]}" == "package_name" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_version" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_uid" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_author_grup" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_copyright" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_licenseUrl" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_require_license_acceptance" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_websiteUrl" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_release_note" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_descripton" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_iconUrl" ] ||
                [ "${COMP_WORDS[$(($tLen-2))]}" == "package_tag" ] ; then
                    a=$a;
                else 
                    COMPREPLY=( $(compgen -W "help default package_name package_version package_uid package_author_grup add package_copyright package_licenseUrl package_require_license_acceptance package_websiteUrl package_docUrl package_release_note package_descripton package_iconUrl package_tag" -- $cur) )
                fi
            fi
        fi
    fi
}   
alias dna=./dna
complete -F dna_completate dna