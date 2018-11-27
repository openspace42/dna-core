#!/usr/bin/env bash
dna_autocomplete()
{
    local cur=${COMP_WORDS[COMP_CWORD]}
    tLen=${#COMP_WORDS[@]}
    

    if [[ "$tLen" -eq "2" ]]; then
        dna_autocomplete_def
    else 
        if [[ "${COMP_WORDS[1]}" == "conf" ]]; then
            dan_autocomplete_conf_base
        fi
    fi
}

dna_autocomplete_def(){
    COMPREPLY=( $(compgen -W "install build run compile conf help" -- $cur) )
}

dan_autocomplete_conf_base(){
    Common="help build add default display package_name package_version package_uid package_author_group package_copyright package_licenseUrl package_require_license_acceptance package_websiteUrl package_docUrl package_author package_release_note  package_description package_iconUrl package_tag"
    tLen=${#COMP_WORDS[@]}
    last=$((tLen-1))
    if [[ "${COMP_WORDS[$(($tLen-2))]}" == "add" ]]; then
        COMPREPLY=( $(compgen -W "owner node_extend dependencies tag" -- $cur) )
    else
    if [[ "${COMP_WORDS[$(($tLen-2))]}" == "default" ]]; then
            COMPREPLY=( $(compgen -W "${Common}" -- $cur) )
        else
            if [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_require_license_acceptance" ]]; then
                COMPREPLY=( $(compgen -W "true false" -- $cur) )
            else
                if [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_author" ]]; then
                    COMPREPLY=( $(compgen -W "author_name author_surname author_nic author_link author_other" -- $cur) )
                else
                    if [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_name" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_version" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_uid" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_author_group" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_copyright" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_licenseUrl" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_require_license_acceptance" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_websiteUrl" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_release_note" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_description" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_iconUrl" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "author_name" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "author_surname" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "author_nic" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "author_link" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "author_other" ]] ||
                    [[ "${COMP_WORDS[$(($tLen-2))]}" == "package_tag" ]] ; then
                        a=$a;
                    else
                        COMPREPLY=( $(compgen -W "${Common}" -- $cur) )
                    fi
                fi
            fi
        fi
    fi
}   
alias dna=./dna
alias dna.min=./dna.min
complete -F dna_autocomplete dna
complete -F dna_autocomplete dna.min